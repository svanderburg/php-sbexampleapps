<?php
namespace SBExampleApps\Literature\Model\CRUD;
use Exception;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBData\Model\Field\DateField;
use SBData\Model\Field\FileField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\NumericIntKeyLinkField;
use SBData\Model\Field\ReadOnlyNumericIntTextField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\TextAreaField;
use SBData\Model\Field\URLField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\Entity\AuthorEntity;
use SBExampleApps\Literature\Model\Entity\PaperEntity;
use SBExampleApps\Literature\Model\FileSet\PaperFileSet;

class PaperCRUDModel extends CRUDModel
{
	public PDO $dbh;

	public ?Form $form = null;

	public ?Form $addAuthorForm = null;

	public ?DBTable $authorsTable = null;

	public bool $hasPDF = false;

	public AuthorizationManager $authorizationManager;

	public function __construct(CRUDPage $crudPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructPaperForm(): void
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

	private function constructAddAuthorForm(): void
	{
		$this->addAuthorForm = new Form(array(
			"__operation" => new HiddenField(true),
			"AUTHOR_ID" => new DBComboBoxField("Author", AuthorEntity::querySummary($this->dbh), true)
		));

		$this->addAuthorForm->fields["__operation"]->importValue("insert_paper_author");
	}

	private function createPaper(): void
	{
		$this->constructPaperForm();

		$row = array(
			"__operation" => "insert_paper"
		);
		$this->form->importValues($row);
	}

	private function viewPaperProperties(): void
	{
		$this->constructPaperForm();

		/* Query the paper and construct a form */
		$stmt = PaperEntity::queryOne($this->dbh, $this->keyFields['paperId']->exportValue(), $this->keyFields['conferenceId']->exportValue());

		if(($row = $stmt->fetch()) === false)
		{
			header("HTTP/1.1 404 Not Found");
			throw new Exception("Cannot find paper with the given ids!");
		}
		else
		{
			$row['__operation'] = "update_paper";
			$this->form->importValues($row);

			function composeAuthorLink(NumericIntKeyLinkField $field, Form $form): string
			{
				$authorId = $field->exportValue();
				return $_SERVER["SCRIPT_NAME"]."/authors/".$authorId;
			}

			function deletePaperAuthorLink(Form $form): string
			{
				$authorId = $form->fields["AUTHOR_ID"]->exportValue();
				return $_SERVER['PHP_SELF']."?__operation=delete_paper_author&amp;AUTHOR_ID=".$authorId.AnchorRow::composePreviousRowParameter($form);
			}

			/* Construct a table containing the authors for this form */
			$this->authorsTable = new DBTable(array(
				"AUTHOR_ID" => new NumericIntKeyLinkField("Id", __NAMESPACE__.'\\composeAuthorLink', true),
				"LastName" => new TextField("Last name", true),
				"FirstName" => new TextField("First name", true)
			), array(
				"Delete" => __NAMESPACE__.'\\deletePaperAuthorLink'
			));

			$this->authorsTable->stmt = PaperEntity::queryAuthors($this->dbh, $this->keyFields['paperId']->exportValue(), $this->keyFields['conferenceId']->exportValue());

			$this->hasPDF = $row['hasPDF'] == 1;
		}
	}

	private function viewPaper(): void
	{
		$this->viewPaperProperties();
		/* Construct a form that can be used to add authors */
		$this->constructAddAuthorForm();
	}

	private function insertPaper(): void
	{
		$this->constructPaperForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$conferenceId = $this->keyFields['conferenceId']->exportValue();

			$paper = $this->form->exportValues();
			$paper['hasPDF'] = PaperFileSet::pdfProvided() ? 1 : 0;

			$paperId = PaperEntity::insert($this->dbh, $paper, $conferenceId);

			if(PaperFileSet::pdfProvided())
				PaperFileSet::insertOrUpdatePDF(dirname($_SERVER["SCRIPT_FILENAME"])."/pdf", $paperId, $conferenceId);

			header("Location: ".$_SERVER["SCRIPT_NAME"]."/conferences/".$conferenceId."/papers/".$paperId);
			exit();
		}
	}

	private function updatePaper(): void
	{
		$this->constructPaperForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$paper = $this->form->exportValues();
			$paper['hasPDF'] = PaperFileSet::pdfProvided() ? 1 : 0;

			$paperId = $this->keyFields['paperId']->exportValue();
			$conferenceId = $this->keyFields['conferenceId']->exportValue();

			PaperEntity::update($this->dbh, $paper, $paperId, $conferenceId);

			if(PaperFileSet::pdfProvided())
				PaperFileSet::insertOrUpdatePDF(dirname($_SERVER["SCRIPT_FILENAME"])."/pdf", $paperId, $conferenceId);

			header("Location: ".$_SERVER["SCRIPT_NAME"]."/conferences/".$conferenceId."/papers/".$paper["PAPER_ID"]);
			exit();
		}
	}

	private function deletePaper(): void
	{
		$paperId = $this->keyFields['paperId']->exportValue();
		$conferenceId = $this->keyFields['conferenceId']->exportValue();

		PaperEntity::remove($this->dbh, $paperId, $conferenceId);
		PaperFileSet::removePDF(dirname($_SERVER["SCRIPT_FILENAME"])."/pdf", $paperId, $conferenceId);
		header("Location: ".$_SERVER['HTTP_REFERER'].AnchorRow::composeRowFragment("paper-row"));
		exit();
	}

	private function insertPaperAuthor(): void
	{
		$this->constructAddAuthorForm();
		$this->addAuthorForm->importValues($_REQUEST);
		$this->addAuthorForm->checkFields();

		if($this->addAuthorForm->checkValid())
		{
			PaperEntity::insertAuthor($this->dbh, $this->keyFields['paperId']->exportValue(), $this->keyFields['conferenceId']->exportValue(), $this->addAuthorForm->fields["AUTHOR_ID"]->exportValue());
			header("Location: ".$_SERVER['HTTP_REFERER']."#authors");
			exit();
		}
		else
			$this->viewPaperProperties();
	}

	private function deletePaperAuthor(): void
	{
		$authorIdField = new TextField("Id", true);
		$authorIdField->importValue($_REQUEST["AUTHOR_ID"]);

		if($authorIdField->checkField("AUTHOR_ID"))
		{
			PaperEntity::removeAuthor($this->dbh, $this->keyFields['paperId']->exportValue(), $this->keyFields['conferenceId']->exportValue(), $authorIdField->exportValue());
			header("Location: ".$_SERVER['HTTP_REFERER'].AnchorRow::composeRowFragment("author-row"));
			exit();
		}
		else
			throw new Exception("Invalid author id!");
	}

	private function deletePaperPDF(): void
	{
		$paperId = $this->keyFields['paperId']->exportValue();
		$conferenceId = $this->keyFields['conferenceId']->exportValue();

		PaperEntity::removePDF($this->dbh, $paperId, $conferenceId);
		PaperFileSet::removePDF(dirname($_SERVER["SCRIPT_FILENAME"])."/pdf", $paperId, $conferenceId);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	public function executeOperation(): void
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
