<?php
namespace SBExampleApps\Literature\Model\CRUD;
use Exception;
use PDO;
use SBLayout\Model\Route;
use SBLayout\Model\BadRequestException;
use SBLayout\Model\PageForbiddenException;
use SBData\Model\Form;
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBData\Model\Field\DateField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\NumericIntKeyLinkField;
use SBData\Model\Field\ReadOnlyNumericIntTextField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\URLField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBData\Model\Value\NaturalNumberValue;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\CRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\Entity\ConferenceEntity;
use SBExampleApps\Literature\Model\Entity\PaperEntity;

class ConferenceCRUDInterface extends CRUDInterface
{
	public CRUDPage $crudPage;

	public PDO $dbh;

	public CRUDForm $form;

	public ?Form $addEditorForm = null;

	public ?DBTable $editorsTable = null;

	public ?DBTable $papersTable = null;

	public AuthorizationManager $authorizationManager;

	public function __construct(Route $route, CRUDPage $crudPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($crudPage);
		$this->route = $route;
		$this->crudPage = $crudPage;
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructConferenceForm(): void
	{
		$this->form = new CRUDForm(array(
			"__operation" => new HiddenField(true),
			"CONFERENCE_ID" => new ReadOnlyNumericIntTextField("Id", false),
			"Name" => new TextField("Name", true, 20, 255),
			"Homepage" => new URLField("Homepage", false),
			"PUBLISHER_ID" => new DBComboBoxField("Publisher", $this->dbh, "SBExampleApps\\Literature\\Model\\Entity\\PublisherEntity::querySummary", "SBExampleApps\\Literature\\Model\\Entity\\PublisherEntity::queryOneSummary", true),
			"Location" => new TextField("Location", true, 20, 255)
		));
	}

	private function constructAddEditorForm(): void
	{
		$this->addEditorForm = new Form(array(
			"__operation" => new HiddenField(true),
			"AUTHOR_ID" => new DBComboBoxField("Editor", $this->dbh, "SBExampleApps\\Literature\\Model\\Entity\\AuthorEntity::querySummary", "SBExampleApps\\Literature\\Model\\Entity\\AuthorEntity::queryOneSummary", true)
		));

		$this->addEditorForm->fields["__operation"]->importValue("insert_conference_author");
	}

	private function createConference(): void
	{
		$this->constructConferenceForm();
		$this->form->setOperation("insert_conference");
	}

	private function insertConference(): void
	{
		$this->constructConferenceForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$conference = $this->form->exportValues();
			$conferenceId = ConferenceEntity::insert($this->dbh, $conference);
			header("Location: ".$_SERVER["PHP_SELF"]."/".rawurlencode($conferenceId));
			exit();
		}
	}

	private function viewConferenceProperties(): void
	{
		/* Query the properties of the requested conference and construct a form from it */
		$this->constructConferenceForm();
		$this->form->importValues($this->crudPage->entity);
		$this->form->setOperation("update_conference");

		/* Construct a table containing the editors for this form */
		$composeAuthorLink = function (NumericIntKeyLinkField $field, Form $form): string
		{
			$authorId = $field->exportValue();
			return $_SERVER["SCRIPT_NAME"]."/authors/".rawurlencode($authorId);
		};

		$deleteConferenceAuthorLink = function (Form $form): string
		{
			$authorId = $form->fields["AUTHOR_ID"]->exportValue();
			return $_SERVER['PHP_SELF']."?".http_build_query(array(
				"__operation" => "delete_conference_author",
				"AUTHOR_ID" => $authorId
			), "", "&amp;", PHP_QUERY_RFC3986).AnchorRow::composeRowParameter($form);
		};

		$this->editorsTable = new DBTable(array(
			"AUTHOR_ID" => new NumericIntKeyLinkField("Id", $composeAuthorLink, true),
			"LastName" => new TextField("Last name", true, 20, 255),
			"FirstName" => new TextField("First name", true, 20, 255)
		), array(
			"Delete" => $deleteConferenceAuthorLink
		));

		$this->editorsTable->stmt = ConferenceEntity::queryEditors($this->dbh, $GLOBALS["query"]["conferenceId"]);

		/* Construct a table containing the papers for this conference */
		$composePaperLink = function (NumericIntKeyLinkField $field, Form $form): string
		{
			$paperId = $field->exportValue();
			return $_SERVER["PHP_SELF"]."/papers/".rawurlencode($paperId);
		};

		$deletePaperLink = function (Form $form): string
		{
			$paperId = $form->fields["PAPER_ID"]->exportValue();
			return $_SERVER['PHP_SELF']."/papers/".rawurlencode($paperId)."?__operation=delete_paper".AnchorRow::composeRowParameter($form);
		};

		$this->papersTable = new DBTable(array(
			"PAPER_ID" => new NumericIntKeyLinkField("Id", $composePaperLink, true),
			"Title" => new TextField("Title", true, 20, 255),
			"Date" => new DateField("Date", true),
			"URL" => new URLField("URL", false),
			"Comment" => new TextField("Comment", false, 20, 255)
		), array(
			"Delete" => $deletePaperLink
		));

		$this->papersTable->stmt = PaperEntity::queryAll($this->dbh, $GLOBALS["query"]["conferenceId"]);
	}

	private function viewConference(): void
	{
		$this->viewConferenceProperties();
		/* Construct a form that can be used to add editors */
		$this->constructAddEditorForm();
	}

	private function updateConference(): void
	{
		$this->constructConferenceForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$conference = $this->form->exportValues();
			ConferenceEntity::update($this->dbh, $conference, $GLOBALS["query"]["conferenceId"]);
			header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"])."/".rawurlencode($conference['CONFERENCE_ID']));
			exit();
		}
	}

	private function deleteConference(): void
	{
		ConferenceEntity::remove($this->dbh, $GLOBALS["query"]["conferenceId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composePreviousRowFragment());
		exit();
	}

	private function insertConferenceEditor(): void
	{
		$this->constructAddEditorForm();
		$this->addEditorForm->importValues($_REQUEST);
		$this->addEditorForm->checkFields();

		if($this->addEditorForm->checkValid())
		{
			ConferenceEntity::insertEditor($this->dbh, $GLOBALS["query"]["conferenceId"], $this->addEditorForm->fields["AUTHOR_ID"]->exportValue());
			header("Location: ".$_SERVER["PHP_SELF"]."#editors");
			exit();
		}
		else
			$this->viewConferenceProperties();
	}

	private function deleteConferenceEditor(): void
	{
		$authorIdValue = new NaturalNumberValue(true);
		$authorIdValue->value = $_REQUEST["AUTHOR_ID"];

		if($authorIdValue->checkValue("AUTHOR_ID"))
		{
			ConferenceEntity::removeEditor($this->dbh, $GLOBALS["query"]["conferenceId"], $authorIdValue->value);
			header("Location: ".$_SERVER["PHP_SELF"].AnchorRow::composePreviousRowFragment("editor-row"));
			exit();
		}
		else
			throw new BadRequestException("Invalid author id!");
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewConference();
		else
		{
			if($this->authorizationManager->authenticated) // Write operations are only allowed when authenticated
			{
				switch($operation)
				{
					case "create_conference":
						$this->createConference();
						break;
					case "insert_conference":
						$this->insertConference();
						break;
					case "update_conference":
						$this->updateConference();
						break;
					case "delete_conference":
						$this->deleteConference();
						break;
					case "insert_conference_author":
						$this->insertConferenceEditor();
						break;
					case "delete_conference_author":
						$this->deleteConferenceEditor();
						break;
				}
			}
			else
				throw new PageForbiddenException("No permissions to modify a conference!");
		}
	}
}
?>
