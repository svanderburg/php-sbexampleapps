<?php
namespace SBExampleApps\Homework\Model\CRUD;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\HiddenNumericIntField;
use SBData\Model\Field\TextField;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\CRUDPage;
use SBExampleApps\Homework\Model\Objects\Exam;

class ExamCRUDInterface extends CRUDInterface
{
	public PDO $dbh;

	public CRUDForm $form;

	public function __construct(CRUDPage $crudPage, PDO $dbh)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
	}

	private function constructTestForm(): void
	{
		$this->form = new CRUDForm(array(
			"__operation" => new HiddenField(true),
			"questionId" => new HiddenNumericIntField(false),
			"answer" => new TextField("Answer", false, 20)
		));
	}

	private function displaySuccessiveQuestion(): void
	{
		if(($row = $_SESSION["exam"]->openSuccessiveQuestion($this->dbh)) !== false)
		{
			$this->form->clearValues();
			$this->form->importValues($row);
			$this->form->setOperation("submit_answer");
		}
	}

	private function viewExam(): void
	{
		$this->constructTestForm();
		$_SESSION["exam"] = new Exam($GLOBALS["query"]["testId"]);
		$this->displaySuccessiveQuestion();
	}

	private function submitAnswer(): void
	{
		$this->constructTestForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			/* Check the answer if the question was an exact one */
			$_SESSION["exam"]->checkProvidedAnswer($this->form->fields["answer"]->exportValue());

			$this->displaySuccessiveQuestion();
		}
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if(session_status() == PHP_SESSION_NONE)
			session_start();

		if($operation === null)
			$this->viewExam();
		else
		{
			switch($operation)
			{
				case "submit_answer":
					$this->submitAnswer();
					break;
			}
		}
	}
}
?>
