<?php
namespace SBExampleApps\Homework\Model\CRUD;
use Exception;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\CheckBoxField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\ReadOnlyNumericIntTextField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Homework\Model\Entity\QuestionEntity;

class QuestionCRUDModel extends CRUDModel
{
	public PDO $dbh;

	public ?Form $form = null;

	public function __construct(CRUDPage $crudPage, PDO $dbh)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
	}

	private function constructQuestionForm(): void
	{
		$this->form = new Form(array(
			"__operation" => new HiddenField(true),
			"QUESTION_ID" => new ReadOnlyNumericIntTextField("Id", false),
			"Question" => new TextField("Question", true, 20, 255),
			"Answer" => new TextField("Answer", true, 20, 255),
			"Exact" => new CheckBoxField("Exact"),
			"TEST_ID" => new HiddenField(true)
		));
	}

	private function createQuestion(): void
	{
		$this->constructQuestionForm();

		$row = array(
			"__operation" => "insert_question",
			"TEST_ID" => $this->keyValues['testId']->value
		);
		$this->form->importValues($row);
	}

	private function viewQuestion(): void
	{
		$this->constructQuestionForm();

		/* Query the question and construct a form */
		$stmt = QuestionEntity::queryOne($this->dbh, $this->keyValues['testId']->value, $this->keyValues['questionId']->value);

		if(($row = $stmt->fetch()) === false)
		{
			header("HTTP/1.1 404 Not Found");
			throw new Exception("Cannot find question with the given ids!");
		}
		else
		{
			$row['__operation'] = "update_question";
			$this->form->importValues($row);
		}
	}

	private function insertQuestion(): void
	{
		$this->constructQuestionForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$question = $this->form->exportValues();
			$questionId = QuestionEntity::insert($this->dbh, $question);

			header("Location: ".$_SERVER["SCRIPT_NAME"]."/tests/".$this->keyValues['testId']->value."/questions/".$questionId);
			exit();
		}
	}

	private function updateQuestion(): void
	{
		$this->constructQuestionForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$question = $this->form->exportValues();
			$testId = $this->keyValues['testId']->value;

			QuestionEntity::update($this->dbh, $question, $testId, $this->keyValues['questionId']->value);

			header("Location: ".$_SERVER["SCRIPT_NAME"]."/tests/".$testId."/questions/".$question["QUESTION_ID"]);
			exit();
		}
	}

	private function deleteQuestion(): void
	{
		QuestionEntity::remove($this->dbh, $this->keyValues['testId']->value, $this->keyValues['questionId']->value);

		header("Location: ".$_SERVER['HTTP_REFERER'].AnchorRow::composePreviousRowFragment());
		exit();
	}
	
	public function executeOperation(): void
	{
		if(array_key_exists("__operation", $_REQUEST))
		{
			switch($_REQUEST["__operation"])
			{
				case "create_question":
					$this->createQuestion();
					break;
				case "insert_question":
					$this->insertQuestion();
					break;
				case "update_question":
					$this->updateQuestion();
					break;
				case "delete_question":
					$this->deleteQuestion();
					break;
				default:
					$this->viewQuestion();
					break;
			}
		}
		else
			$this->viewQuestion();
	}
}
?>
