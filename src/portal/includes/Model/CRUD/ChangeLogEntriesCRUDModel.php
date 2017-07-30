<?php
namespace SBExampleApps\Portal\Model\CRUD;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\DateField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\NumericIntTextField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\DBTable;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Portal\Model\Entity\ChangeLogEntriesEntity;

class ChangeLogEntriesCRUDModel extends CRUDModel
{
	public $dbh;

	public $addEntryForm = null;

	public $table = null;

	public $submittedForm = null;

	public $authorizationManager;

	public function __construct(CRUDPage $crudPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructChangeLogEntriesTable()
	{
		function deleteChangeLogLink(Form $form)
		{
			return "?__operation=remove_changelogentry&amp;LOG_ID=".$form->fields["LOG_ID"]->value."&amp;__id=".$form->fields["__id"]->value;
		}
	
		$this->table = new DBTable(array(
			"LOG_ID" => new TextField("Version", true, 10, 255),
			"Date" => new DateField("Date", true, true),
			"Summary" => new TextField("Summary", true, 30, 255),
			"old_LOG_ID" => new HiddenField(true)
		), array(
			"Delete" => __NAMESPACE__.'\\deleteChangeLogLink'
		));
	}

	private function constructAddEntryForm()
	{
		$this->addEntryForm = new Form(array(
			"__operation" => new HiddenField(true),
			"LOG_ID" => new TextField("Version", true, 10, 255),
			"Date" => new DateField("Date", true, true),
			"Summary" => new TextField("Summary", true, 30, 255)
		));
	}

	private function viewChangeLogEntries()
	{
		$this->constructChangeLogEntriesTable();
		$this->table->stmt = ChangeLogEntriesEntity::queryAll($this->dbh);
	}

	private function createChangeLogEntry()
	{
		$this->constructAddEntryForm();
		$row = array("__operation" => "insert_changelogentry");
		$this->addEntryForm->importValues($row);
	}

	private function insertChangeLogEntry()
	{
		$this->constructAddEntryForm();
		$this->addEntryForm->importValues($_REQUEST);
		$this->addEntryForm->checkFields();

		if($this->addEntryForm->checkValid())
		{
			$entry = $this->addEntryForm->exportValues();
			ChangeLogEntriesEntity::insert($this->dbh, $entry);
			header("Location: ".$_SERVER["PHP_SELF"]);
			exit();
		}
	}

	private function updateChangeLogEntry()
	{
		$this->constructChangeLogEntriesTable();

		/* Validate record */
		$this->submittedForm = $this->table->constructForm();
		$this->submittedForm->importValues($_POST);
		$this->submittedForm->checkFields();

		/* Update the record if it is valid */
		if($this->submittedForm->checkValid())
		{
			$entry = $this->submittedForm->exportValues();
			ChangeLogEntriesEntity::update($this->dbh, $entry, $entry["old_LOG_ID"]);
		}

		$this->table->stmt = ChangeLogEntriesEntity::queryAll($this->dbh);
	}

	private function removeChangeLogEntry()
	{
		$logIdField = new TextField("Id", true);
		$logIdField->value = $_REQUEST["LOG_ID"];

		$idField = new NumericIntTextField("Id", true);
		$idField->value = $_REQUEST["__id"];

		if($logIdField->checkField("Id") && $idField->checkField("Id"))
		{
			ChangeLogEntriesEntity::remove($this->dbh, $_REQUEST["LOG_ID"]);
			header("Location: ".$_SERVER["PHP_SELF"]."#table-row-".$_REQUEST["__id"]);
			exit();
		}
		else
			throw new Exceptions("The keys are not valid!");
	}

	public function executeOperation()
	{
		if($this->authorizationManager->authenticated) // Write operations are only allowed when authenticated
		{
			if(array_key_exists("__operation", $_REQUEST))
			{
				switch($_REQUEST["__operation"])
				{
					case "insert_changelogentry":
						$this->insertChangeLogEntry();
						break;
					case "create_changelogentry":
						$this->createChangeLogEntry();
						break;
					case "remove_changelogentry":
						$this->removeChangeLogEntry();
						break;
					default:
						$this->viewChangeLogEntries();
				}
			}
			else if(count($_POST) > 0)
				$this->updateChangeLogEntry();
			else
				$this->viewChangeLogEntries();
		}
		else
			$this->viewChangeLogEntries();
	}
}
?>
