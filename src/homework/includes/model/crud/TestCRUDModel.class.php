<?php
require_once("data/model/Form.class.php");
require_once("data/model/table/DBTable.class.php");
require_once("data/model/field/KeyLinkField.class.php");
require_once("data/model/field/TextField.class.php");
require_once("data/model/field/HiddenField.class.php");
require_once("data/model/field/CheckBoxField.class.php");
require_once("crud/model/CRUDModel.class.php");
require_once("model/entities/TestEntity.class.php");

class TestCRUDModel extends CRUDModel
{
	public $dbh;

	public $form = null;

	public $table = null;

	public function __construct(CRUDPage $crudPage, PDO $dbh)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
	}

	private function constructTestForm()
	{
		$this->form = new Form(array(
			"__operation" => new HiddenField(true),
			"TEST_ID" => new TextField("Id", true, 20, 255),
			"Title" => new TextField("Title", true, 20, 255)
		));
	}

	private function createTest()
	{
		$this->constructTestForm();

		$row = array(
			"__operation" => "insert_test"
		);
		$this->form->importValues($row);
	}

	private function insertTest()
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

	private function viewTest()
	{
		/* Query the properties of the requested test and construct a form and table from it */
		$this->constructTestForm();

		$stmt = TestEntity::queryOne($this->dbh, $this->keyFields['testId']->value);

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
			function composeQuestionLink(KeyLinkField $field, Form $form)
			{
				return $_SERVER["PHP_SELF"]."/questions/".$field->value;
			}

			$this->table = new DBTable(array(
				"QUESTION_ID" => new KeyLinkField("Id", "composeQuestionLink", true),
				"Question" => new TextField("Question", true),
				"Answer" => new TextField("Answer", true),
				"Exact" => new CheckBoxField("Exact")
			));

			$this->table->stmt = TestEntity::queryAllQuestions($this->dbh, $this->keyFields['testId']->value);
		}
	}

	private function updateTest()
	{
		$this->constructTestForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$test = $this->form->exportValues();
			TestEntity::update($this->dbh, $test, $this->keyFields['testId']->value);
			header("Location: ".$_SERVER["SCRIPT_NAME"]."/tests/".$test['TEST_ID']);
			exit();
		}
	}

	private function deleteTest()
	{
		TestEntity::remove($this->dbh, $this->keyFields['testId']->value);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	public function executeOperation()
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
