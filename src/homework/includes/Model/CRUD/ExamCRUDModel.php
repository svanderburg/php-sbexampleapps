<?php
namespace SBExampleApps\Homework\Model\CRUD;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\TextField;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Homework\Model\Objects\Exam;

class ExamCRUDModel extends CRUDModel
{
	public $dbh;

	public $form = null;

	public function __construct(CRUDPage $crudPage, PDO $dbh)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
	}

	private function constructTestForm()
	{
		$this->form = new Form(array(
			"__operation" => new HiddenField(true),
			"questionId" => new HiddenField(false),
			"answer" => new TextField("Answer", false, 20)
		));
	}

	private function displaySuccessiveQuestion()
	{
		if(($row = $_SESSION["exam"]->openSuccessiveQuestion($this->dbh)) !== false)
		{
			$this->form->clearValues();
			$row['__operation'] = "submit_answer";
			$this->form->importValues($row);
		}
	}

	private function viewExam()
	{
		$this->constructTestForm();
		$_SESSION["exam"] = new Exam($this->keyFields["testId"]->value);
		$this->displaySuccessiveQuestion();
	}

	private function submitAnswer()
	{
		$this->constructTestForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			/* Check the answer if the question was an exact one */
			$_SESSION["exam"]->checkProvidedAnswer($this->form->fields["answer"]->value);

			$this->displaySuccessiveQuestion();
		}
	}

	public function executeOperation()
	{
		if(session_status() == PHP_SESSION_NONE)
			session_start();

		if(array_key_exists("__operation", $_REQUEST))
		{
			switch($_REQUEST["__operation"])
			{
				case "submit_answer":
					$this->submitAnswer();
					break;
				default:
					$this->viewExam();
			}
		}
		else
			$this->viewExam();
	}
}
?>
