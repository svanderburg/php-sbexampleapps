<?php
namespace SBExampleApps\Portal\Model\CRUD;
use Exception;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\DateField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\NumericIntTextField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBData\Model\Value\Value;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Portal\Model\Entity\ChangeLogEntriesEntity;

class ChangeLogEntriesCRUDModel extends CRUDModel
{
	public PDO $dbh;

	public ?Form $addEntryForm = null;

	public ?DBTable $table = null;

	public ?Form $submittedForm = null;

	public AuthorizationManager $authorizationManager;

	public function __construct(CRUDPage $crudPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructChangeLogEntriesTable(): void
	{
		function deleteChangeLogLink(Form $form): string
		{
			$logId = $form->fields["LOG_ID"]->exportValue();
			return "?__operation=remove_changelogentry&amp;LOG_ID=".$logId.AnchorRow::composeRowParameter($form);
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

	private function constructAddEntryForm(): void
	{
		$this->addEntryForm = new Form(array(
			"__operation" => new HiddenField(true),
			"LOG_ID" => new TextField("Version", true, 10, 255),
			"Date" => new DateField("Date", true, true),
			"Summary" => new TextField("Summary", true, 30, 255)
		));
	}

	private function viewChangeLogEntries(): void
	{
		$this->constructChangeLogEntriesTable();
		$this->table->stmt = ChangeLogEntriesEntity::queryAll($this->dbh);
	}

	private function createChangeLogEntry(): void
	{
		$this->constructAddEntryForm();
		$row = array("__operation" => "insert_changelogentry");
		$this->addEntryForm->importValues($row);
	}

	private function insertChangeLogEntry(): void
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

	private function updateChangeLogEntry(): void
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

	private function removeChangeLogEntry(): void
	{
		$logIdValue = new Value(true, 255);
		$logIdValue->value = $_REQUEST["LOG_ID"];

		if($logIdValue->checkValue("Id"))
		{
			ChangeLogEntriesEntity::remove($this->dbh, $logIdValue->value);
			header("Location: ".$_SERVER["PHP_SELF"].AnchorRow::composePreviousRowFragment());
			exit();
		}
		else
			throw new Exceptions("The log id is not valid!");
	}

	public function executeOperation(): void
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
