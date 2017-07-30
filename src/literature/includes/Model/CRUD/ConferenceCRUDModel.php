<?php
namespace SBExampleApps\Literature\Model\CRUD;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBData\Model\Field\DateField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\KeyLinkField;
use SBData\Model\Field\ReadOnlyNumericIntTextField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\URLField;
use SBData\Model\Table\DBTable;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\Entity\AuthorEntity;
use SBExampleApps\Literature\Model\Entity\ConferenceEntity;
use SBExampleApps\Literature\Model\Entity\PaperEntity;
use SBExampleApps\Literature\Model\Entity\PublisherEntity;

class ConferenceCRUDModel extends CRUDModel
{
	public $dbh;

	public $form = null;

	public $addEditorForm = null;

	public $editorsTable = null;

	public $papersTable = null;

	public $authorizationManager;

	public function __construct(CRUDPage $crudPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructConferenceForm()
	{
		$this->form = new Form(array(
			"__operation" => new HiddenField(true),
			"CONFERENCE_ID" => new ReadOnlyNumericIntTextField("Id", false),
			"Name" => new TextField("Name", true, 20, 255),
			"Homepage" => new URLField("Homepage", false),
			"PUBLISHER_ID" => new DBComboBoxField("Publisher", PublisherEntity::querySummary($this->dbh), true),
			"Location" => new TextField("Location", true, 20, 255)
		));
	}

	private function constructAddEditorForm()
	{
		$this->addEditorForm = new Form(array(
			"__operation" => new HiddenField(true),
			"AUTHOR_ID" => new DBComboBoxField("Editor", AuthorEntity::querySummary($this->dbh), true)
		));

		$this->addEditorForm->fields["__operation"]->value = "insert_conference_author";
	}

	private function createConference()
	{
		$this->constructConferenceForm();

		$row = array(
			"__operation" => "insert_conference"
		);
		$this->form->importValues($row);
	}

	private function insertConference()
	{
		$this->constructConferenceForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$conference = $this->form->exportValues();
			$conferenceId = ConferenceEntity::insert($this->dbh, $conference);
			header("Location: ".$_SERVER["SCRIPT_NAME"]."/conferences/".$conferenceId);
			exit();
		}
	}

	private function viewConferenceProperties()
	{
		/* Query the properties of the requested conference and construct a form from it */
		$this->constructConferenceForm();

		$stmt = ConferenceEntity::queryOne($this->dbh, $this->keyFields['conferenceId']->value);

		if(($row = $stmt->fetch()) === false)
		{
			header("HTTP/1.1 404 Not Found");
			throw new Exception("Cannot find conference with this id!");
		}
		else
		{
			$row['__operation'] = "update_conference";
			$this->form->importValues($row);

			/* Construct a table containing the editors for this form */
			function composeAuthorLink(KeyLinkField $field, Form $form)
			{
				return $_SERVER["SCRIPT_NAME"]."/authors/".$field->value;
			}

			function deleteConferenceAuthorLink(Form $form)
			{
				return $_SERVER['PHP_SELF']."?__operation=delete_conference_author&amp;AUTHOR_ID=".$form->fields["AUTHOR_ID"]->value;
			}

			$this->editorsTable = new DBTable(array(
				"AUTHOR_ID" => new KeyLinkField("Id", __NAMESPACE__.'\\composeAuthorLink', true),
				"LastName" => new TextField("Last name", true, 20, 255),
				"FirstName" => new TextField("First name", true, 20, 255)
			), array(
				"Delete" => __NAMESPACE__.'\\deleteConferenceAuthorLink'
			));

			$this->editorsTable->stmt = ConferenceEntity::queryEditors($this->dbh, $this->keyFields['conferenceId']->value);

			/* Construct a table containing the papers for this conference */
			function composePaperLink(KeyLinkField $field, Form $form)
			{
				return $_SERVER["PHP_SELF"]."/papers/".$field->value;
			}

			function deletePaperLink(Form $form)
			{
				return $_SERVER['PHP_SELF']."/papers/".$form->fields["PAPER_ID"]->value."?__operation=delete_paper";
			}

			$this->papersTable = new DBTable(array(
				"PAPER_ID" => new KeyLinkField("Id", __NAMESPACE__.'\\composePaperLink', true),
				"Title" => new TextField("Title", true, 20, 255),
				"Date" => new DateField("Date", true),
				"URL" => new URLField("URL", false),
				"Comment" => new TextField("Comment", false, 20, 255)
			), array(
				"Delete" => __NAMESPACE__.'\\deletePaperLink'
			));

			$this->papersTable->stmt = PaperEntity::queryAll($this->dbh, $this->keyFields['conferenceId']->value);
		}
	}

	private function viewConference()
	{
		$this->viewConferenceProperties();
		/* Construct a form that can be used to add editors */
		$this->constructAddEditorForm();
	}

	private function updateConference()
	{
		$this->constructConferenceForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$conference = $this->form->exportValues();
			ConferenceEntity::update($this->dbh, $conference, $this->keyFields['conferenceId']->value);
			header("Location: ".$_SERVER["SCRIPT_NAME"]."/conferences/".$conference['CONFERENCE_ID']);
			exit();
		}
	}

	private function deleteConference()
	{
		ConferenceEntity::remove($this->dbh, $this->keyFields['conferenceId']->value);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	private function insertConferenceAuthor()
	{
		$this->constructAddEditorForm();
		$this->addEditorForm->importValues($_REQUEST);
		$this->addEditorForm->checkFields();

		if($this->addEditorForm->checkValid())
		{
			ConferenceEntity::insertEditor($this->dbh, $this->keyFields['conferenceId']->value, $this->addEditorForm->fields["AUTHOR_ID"]->value);
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit();
		}
		else
			$this->viewConferenceProperties();
	}

	private function deleteConferenceAuthor()
	{
		$authorIdField = new TextField("Id", true);
		$authorIdField->value = $_REQUEST["AUTHOR_ID"];

		if($authorIdField->checkField("AUTHOR_ID"))
		{
			ConferenceEntity::removeEditor($this->dbh, $this->keyFields['conferenceId']->value, $authorIdField->value);
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit();
		}
		else
			throw new Exception("Invalid author id!");
	}

	public function executeOperation()
	{
		if(array_key_exists("__operation", $_REQUEST))
		{
			if($this->authorizationManager->authenticated) // Write operations are only allowed when authenticated
			{
				switch($_REQUEST["__operation"])
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
						$this->insertConferenceAuthor();
						break;
					case "delete_conference_author":
						$this->deleteConferenceAuthor();
						break;
					default:
						$this->viewConference();
				}
			}
			else
			{
				header("HTTP/1.1 403 Forbidden");
				throw new Exception("No permissions to modify a conference!");
			}
		}
		else
			$this->viewConference();
	}
}
?>
