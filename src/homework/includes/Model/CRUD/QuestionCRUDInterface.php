<?php
namespace SBExampleApps\Homework\Model\CRUD;
use Exception;
use PDO;
use SBLayout\Model\Route;
use SBData\Model\Field\CheckBoxField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\ReadOnlyNaturalNumberTextField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\CRUDPage;
use SBExampleApps\Homework\Model\Entity\QuestionEntity;

class QuestionCRUDInterface extends CRUDInterface
{
	public PDO $dbh;

	public CRUDPage $crudPage;

	public Route $route;

	public CRUDForm $form;

	public function __construct(Route $route, CRUDPage $crudPage, PDO $dbh)
	{
		parent::__construct($crudPage);
		$this->route = $route;
		$this->crudPage = $crudPage;
		$this->dbh = $dbh;
	}

	private function constructQuestionForm(): void
	{
		$this->form = new CRUDForm(array(
			"QUESTION_ID" => new ReadOnlyNaturalNumberTextField("Id", false),
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
			"TEST_ID" => $GLOBALS["query"]["testId"]
		);
		$this->form->importValues($row);
		$this->form->setOperation("insert_question");
	}

	private function viewQuestion(): void
	{
		$this->constructQuestionForm();
		$this->form->importValues($this->crudPage->entity);
		$this->form->setOperation("update_question");
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

			header("Location: ".RouteUtils::composeSelfURL()."/".rawurlencode($questionId));
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
			$testId = $GLOBALS["query"]["testId"];

			QuestionEntity::update($this->dbh, $question, $testId, $GLOBALS["query"]["questionId"]);

			header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"])."/".rawurlencode($question["QUESTION_ID"]));
			exit();
		}
	}

	private function deleteQuestion(): void
	{
		QuestionEntity::remove($this->dbh, $GLOBALS["query"]["testId"], $GLOBALS["query"]["questionId"]);

		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composePreviousRowFragment());
		exit();
	}
	
	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewQuestion();
		else
		{
			switch($operation)
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
			}
		}
	}
}
?>
