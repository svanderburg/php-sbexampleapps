<?php
namespace SBExampleApps\Users\Model\CRUD;
use Exception;
use PDO;
use SBLayout\Model\Route;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\OperationParamPage;
use SBExampleApps\Users\Model\Entity\SystemEntity;

class SystemCRUDInterface extends CRUDInterface
{
	public OperationParamPage $operationParamPage;

	public PDO $dbh;

	public CRUDForm $form;

	public function __construct(Route $route, OperationParamPage $operationParamPage, PDO $dbh)
	{
		parent::__construct($operationParamPage);
		$this->route = $route;
		$this->operationParamPage = $operationParamPage;
		$this->dbh = $dbh;
	}

	private function constructSystemForm(): void
	{
		$this->form = new CRUDForm(array(
			"SYSTEM_ID" => new TextField("Id", true, 20, 255),
			"Description" => new TextField("Description", true, 20, 255)
		));
	}

	private function createSystem(): void
	{
		$this->constructSystemForm();
		$this->form->setOperation("insert_system");
	}

	private function insertSystem(): void
	{
		$this->constructSystemForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$system = $this->form->exportValues();
			SystemEntity::insert($this->dbh, $system);
			header("Location: ".RouteUtils::composeSelfURL()."/".rawurlencode($system["SYSTEM_ID"]));
			exit();
		}
	}

	private function viewSystem()
	{
		$this->constructSystemForm();
		$this->form->importValues($this->operationParamPage->entity);
		$this->form->setOperation("update_system");
	}

	private function updateSystem(): void
	{
		$this->constructSystemForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$system = $this->form->exportValues();
			SystemEntity::update($this->dbh, $system, $GLOBALS["query"]["systemId"]);
			header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"])."/".rawurlencode($system['SYSTEM_ID']));
			exit();
		}
	}

	private function deleteSystem(): void
	{
		SystemEntity::remove($this->dbh, $GLOBALS["query"]["systemId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composePreviousRowFragment());
		exit();
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewSystem();
		else
		{
			switch($operation)
			{
				case "create_system":
					$this->createSystem();
					break;
				case "insert_system":
					$this->insertSystem();
					break;
				case "update_system":
					$this->updateSystem();
					break;
				case "delete_system":
					$this->deleteSystem();
					break;
			}
		}
	}
}
?>
