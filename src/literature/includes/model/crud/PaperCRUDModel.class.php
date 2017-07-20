<?php
require_once("data/model/Form.class.php");
require_once("data/model/field/HiddenField.class.php");
require_once("data/model/field/TextField.class.php");
require_once("data/model/field/DateField.class.php");
require_once("data/model/field/TextAreaField.class.php");
require_once("data/model/field/URLField.class.php");
require_once("data/model/field/FileField.class.php");
require_once("data/model/field/ReadOnlyNumericIntTextField.class.php");
require_once("crud/model/CRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");
require_once("model/entities/PaperEntity.class.php");
require_once("model/entities/AuthorEntity.class.php");
require_once("model/fileset/PaperFileSet.class.php");

class PaperCRUDModel extends CRUDModel
{
	public $dbh;

	public $form = null;

	public $addAuthorForm = null;

	public $authorsTable = null;

	public $hasPDF = false;

	public $authorizationManager;

	public function __construct(CRUDPage $crudPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructPaperForm()
	{
		$this->form = new Form(array(
			"__operation" => new HiddenField(true),
			"PAPER_ID" => new ReadOnlyNumericIntTextField("Id", false),
			"Title" => new TextField("Title", true, 20, 255),
			"Date" => new DateField("Date", true, 20, 255),
			"Abstract" => new TextAreaField("Abstract", false, 60, 20),
			"URL" => new URLField("URL", false),
			"PDF" => new FileField("PDF", "application/pdf", false),
			"Comment" => new TextField("Comment", false, 20, 255)
		));
	}

	private function constructAddAuthorForm()
	{
		$this->addAuthorForm = new Form(array(
			"__operation" => new HiddenField(true),
			"AUTHOR_ID" => new DBComboBoxField("Author", AuthorEntity::querySummary($this->dbh), true)
		));

		$this->addAuthorForm->fields["__operation"]->value = "insert_paper_author";
	}

	private function createPaper()
	{
		$this->constructPaperForm();

		$row = array(
			"__operation" => "insert_paper"
		);
		$this->form->importValues($row);
	}

	private function viewPaperProperties()
	{
		$this->constructPaperForm();

		/* Query the paper and construct a form */
		$stmt = PaperEntity::queryOne($this->dbh, $this->keyFields['paperId']->value, $this->keyFields['conferenceId']->value);

		if(($row = $stmt->fetch()) === false)
		{
			header("HTTP/1.1 404 Not Found");
			throw new Exception("Cannot find paper with the given ids!");
		}
		else
		{
			$row['__operation'] = "update_paper";
			$this->form->importValues($row);

			function composeAuthorLink(KeyLinkField $field, Form $form)
			{
				return $_SERVER["SCRIPT_NAME"]."/authors/".$field->value;
			}

			function deletePaperAuthorLink(Form $form)
			{
				return $_SERVER['PHP_SELF']."?__operation=delete_paper_author&amp;AUTHOR_ID=".$form->fields["AUTHOR_ID"]->value;
			}

			/* Construct a table containing the authors for this form */
			$this->authorsTable = new DBTable(array(
				"AUTHOR_ID" => new KeyLinkField("Id", "composeAuthorLink", true),
				"LastName" => new TextField("Last name", true),
				"FirstName" => new TextField("First name", true)
			), array(
				"Delete" => "deletePaperAuthorLink"
			));

			$this->authorsTable->stmt = PaperEntity::queryAuthors($this->dbh, $this->keyFields['paperId']->value, $this->keyFields['conferenceId']->value);

			$this->hasPDF = $row['hasPDF'] == 1;
		}
	}

	private function viewPaper()
	{
		$this->viewPaperProperties();
		/* Construct a form that can be used to add authors */
		$this->constructAddAuthorForm();
	}

	private function insertPaper()
	{
		$this->constructPaperForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$paper = $this->form->exportValues();
			$paper['hasPDF'] = PaperFileSet::pdfProvided() ? 1 : 0;
			$paperId = PaperEntity::insert($this->dbh, $paper, $this->keyFields['conferenceId']->value);
			PaperFileSet::insertOrUpdatePDF(dirname($_SERVER["SCRIPT_FILENAME"])."/pdf", $paperId, $this->keyFields['conferenceId']->value);

			header("Location: ".$_SERVER["SCRIPT_NAME"]."/conferences/".$this->keyFields['conferenceId']->value."/papers/".$paperId);
			exit();
		}
	}

	private function updatePaper()
	{
		$this->constructPaperForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$paper = $this->form->exportValues();
			
			if(PaperFileSet::pdfProvided())
				$paper['hasPDF'] = 1;

			PaperEntity::update($this->dbh, $paper, $this->keyFields['paperId']->value, $this->keyFields['conferenceId']->value);
			PaperFileSet::insertOrUpdatePDF(dirname($_SERVER["SCRIPT_FILENAME"])."/pdf", $this->keyFields['paperId']->value, $this->keyFields['conferenceId']->value);

			header("Location: ".$_SERVER["SCRIPT_NAME"]."/conferences/".$this->keyFields['conferenceId']->value."/papers/".$paper["PAPER_ID"]);
			exit();
		}
	}

	private function deletePaper()
	{
		PaperEntity::remove($this->dbh, $this->keyFields['paperId']->value, $this->keyFields['conferenceId']->value);
		PaperFileSet::removePDF(dirname($_SERVER["SCRIPT_FILENAME"])."/pdf", $this->keyFields['paperId']->value, $this->keyFields['conferenceId']->value);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	private function insertPaperAuthor()
	{
		$this->constructAddAuthorForm();
		$this->addAuthorForm->importValues($_REQUEST);
		$this->addAuthorForm->checkFields();

		if($this->addAuthorForm->checkValid())
		{
			PaperEntity::insertAuthor($this->dbh, $this->keyFields['paperId']->value, $this->keyFields['conferenceId']->value, $this->addAuthorForm->fields["AUTHOR_ID"]->value);
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit();
		}
		else
			$this->viewPaperProperties();
	}

	private function deletePaperAuthor()
	{
		$authorIdField = new TextField("Id", true);
		$authorIdField->value = $_REQUEST["AUTHOR_ID"];

		if($authorIdField->checkField("AUTHOR_ID"))
		{
			PaperEntity::removeAuthor($this->dbh, $this->keyFields['paperId']->value, $this->keyFields['conferenceId']->value, $authorIdField->value);
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit();
		}
		else
			throw new Exception("Invalid author id!");
	}

	private function deletePaperPDF()
	{
		PaperEntity::removePDF($this->dbh, $this->keyFields['paperId']->value, $this->keyFields['conferenceId']->value);
		PaperFileSet::removePDF(dirname($_SERVER["SCRIPT_FILENAME"])."/pdf", $this->keyFields['paperId']->value, $this->keyFields['conferenceId']->value);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	public function executeOperation()
	{
		if(array_key_exists("__operation", $_REQUEST))
		{
			if($this->authorizationManager->authenticated) // Write operations are only allowed when authenticated
			{
				switch($_REQUEST["__operation"])
				{
					case "create_paper":
						$this->createPaper();
						break;
					case "insert_paper":
						$this->insertPaper();
						break;
					case "update_paper":
						$this->updatePaper();
						break;
					case "delete_paper":
						$this->deletePaper();
						break;
					case "insert_paper_author":
						$this->insertPaperAuthor();
						break;
					case "delete_paper_author":
						$this->deletePaperAuthor();
						break;
					case "delete_paper_pdf":
						$this->deletePaperPDF();
						break;
					default:
						$this->viewPaper();
						break;
				}
			}
			else
			{
				header("HTTP/1.1 403 Forbidden");
				throw new Exception("No permissions to modify a paper!");
			}
		}
		else
			$this->viewPaper();
	}
}
?>
