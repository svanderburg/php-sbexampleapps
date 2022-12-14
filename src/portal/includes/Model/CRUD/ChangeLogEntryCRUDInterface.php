<?php
namespace SBExampleApps\Portal\Model\CRUD;
use Exception;
use PDO;
use SBLayout\Model\Route;
use SBLayout\Model\PageForbiddenException;
use SBData\Model\Field\DateField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\CRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Portal\Model\Entity\ChangeLogEntriesEntity;

class ChangeLogEntryCRUDInterface extends CRUDInterface
{
	public PDO $dbh;

	public CRUDForm $form;

	public AuthorizationManager $authorizationManager;

	public function __construct(Route $route, CRUDPage $crudPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($crudPage);
		$this->route = $route;
		$this->crudPage = $crudPage;
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructForm(): void
	{
		$this->form = new CRUDForm(array(
			"LOG_ID" => new TextField("Version", true, 10, 255),
			"Date" => new DateField("Date", true, true),
			"Summary" => new TextField("Summary", true, 30, 255)
		));
	}

	private function viewChangeLogEntry(): void
	{
		$this->constructForm();
		$row = ChangeLogEntriesEntity::queryOne($GLOBALS["query"]["logId"]);
		$this->form->importValues($row);
	}

	private function createChangeLogEntry(): void
	{
		$this->constructForm();
		$this->form->setOperation("insert_changelogentry");
	}

	private function insertChangeLogEntry(): void
	{
		$this->constructForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$entry = $this->form->exportValues();
			ChangeLogEntriesEntity::insert($this->dbh, $entry);
			header("Location: ".RouteUtils::composeSelfURL());
			exit();
		}
	}

	private function removeChangeLogEntry(): void
	{
		ChangeLogEntriesEntity::remove($this->dbh, $GLOBALS["query"]["logId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composePreviousRowFragment());
		exit();
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewChangeLogEntry();
		else
		{
			if($this->authorizationManager->authenticated) // Write operations are only allowed when authenticated
			{
				switch($operation)
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
				}
			}
			else
				throw new PageForbiddenException("No permissions to modify a changelog entry!");
		}
	}
}
?>
