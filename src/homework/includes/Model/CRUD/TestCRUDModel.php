<?php
namespace SBExampleApps\Homework\Model\CRUD;
use Exception;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\CheckBoxField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\NumericIntKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Homework\Model\Entity\TestEntity;

class TestCRUDModel extends CRUDModel
{
	public PDO $dbh;

	public ?Form $form = null;

	public ?DBTable $table = null;

	public function __construct(CRUDPage $crudPage, PDO $dbh)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
	}

	private function constructTestForm(): void
	{
		$this->form = new Form(array(
			"__operation" => new HiddenField(true),
			"TEST_ID" => new TextField("Id", true, 20, 255),
			"Title" => new TextField("Title", true, 20, 255)
		));
	}

	private function createTest(): void
	{
		$this->constructTestForm();

		$row = array(
			"__operation" => "insert_test"
		);
		$this->form->importValues($row);
	}

	private function insertTest(): void
	{
		$this->constructTestForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$test = $this->form->exportValues();
			TestEntity::insert($this->dbh, $test);
			header("Location: ".$_SERVER["SCRIPT_NAME"]."/tests/".$test['TEST_ID']);
			exit();
		}
	}

	private function viewTest(): void
	{
		/* Query the properties of the requested test and construct a form and table from it */
		$this->constructTestForm();

		$stmt = TestEntity::queryOne($this->dbh, $this->keyParameterMap->values['testId']->value);

		if(($row = $stmt->fetch()) === false)
		{
			header("HTTP/1.1 404 Not Found");
			throw new Exception("Cannot find test with this id!");
		}
		else
		{
			$row['__operation'] = "update_test";
			$this->form->importValues($row);

			/* Construct a table containing the questions for this form */
			function composeQuestionLink(NumericIntKeyLinkField $field, Form $form): string
			{
				$questionId = $field->exportValue();
				return $_SERVER["PHP_SELF"]."/questions/".$questionId;
			}

			function deleteQuestionLink(Form $form): string
			{
				$questionId = $form->fields['QUESTION_ID']->exportValue();
				return $_SERVER['PHP_SELF']."/questions/".$questionId."?__operation=delete_question".AnchorRow::composeRowParameter($form);
			}

			$this->table = new DBTable(array(
				"QUESTION_ID" => new NumericIntKeyLinkField("Id", __NAMESPACE__.'\\composeQuestionLink', true),
				"Question" => new TextField("Question", true),
				"Answer" => new TextField("Answer", true),
				"Exact" => new CheckBoxField("Exact")
			), array(
				"Delete" => __NAMESPACE__.'\\deleteQuestionLink'
			));

			$this->table->stmt = TestEntity::queryAllQuestions($this->dbh, $this->keyParameterMap->values['testId']->value);
		}
	}

	private function updateTest(): void
	{
		$this->constructTestForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$testId = $this->keyParameterMap->values['testId']->value;
			$test = $this->form->exportValues();
			TestEntity::update($this->dbh, $test, $testId);
			header("Location: ".$_SERVER["SCRIPT_NAME"]."/tests/".$test['TEST_ID']);
			exit();
		}
	}

	private function deleteTest(): void
	{
		TestEntity::remove($this->dbh, $this->keyParameterMap->values['testId']->value);
		header("Location: ".$_SERVER['HTTP_REFERER'].AnchorRow::composePreviousRowFragment());
		exit();
	}

	public function executeOperation(): void
	{
		if(array_key_exists("__operation", $_REQUEST))
		{
			switch($_REQUEST["__operation"])
			{
				case "create_test":
					$this->createTest();
					break;
				case "insert_test":
					$this->insertTest();
					break;
				case "update_test":
					$this->updateTest();
					break;
				case "delete_test":
					$this->deleteTest();
					break;
				default:
					$this->viewTest();
			}
		}
		else
			$this->viewTest();
	}
}
?>
