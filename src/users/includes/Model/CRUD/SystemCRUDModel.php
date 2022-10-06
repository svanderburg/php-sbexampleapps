<?php
namespace SBExampleApps\Users\Model\CRUD;
use Exception;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Users\Model\Entity\SystemEntity;

class SystemCRUDModel extends CRUDModel
{
	public PDO $dbh;

	public ?Form $form = null;

	public function __construct(CRUDPage $crudPage, PDO $dbh)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
	}

	private function constructSystemForm(): void
	{
		$this->form = new Form(array(
			"__operation" => new HiddenField(true),
			"SYSTEM_ID" => new TextField("Id", true, 20, 255),
			"Description" => new TextField("Description", true, 20, 255)
		));
	}

	private function createSystem(): void
	{
		$this->constructSystemForm();

		$row = array(
			"__operation" => "insert_system"
		);
		$this->form->importValues($row);
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
			header("Location: ".$_SERVER["SCRIPT_NAME"]."/systems/".$system['SYSTEM_ID']);
			exit();
		}
	}

	private function viewSystem()
	{
		/* Query the properties of the requested system and construct a form from it */
		$this->constructSystemForm();

		$stmt = SystemEntity::queryOne($this->dbh, $this->keyFields['systemId']->exportValue());

		if(($row = $stmt->fetch()) === false)
		{
			header("HTTP/1.1 404 Not Found");
			throw new Exception("Cannot find system with this id!");
		}
		else
		{
			$row['__operation'] = "update_system";
			$this->form->importValues($row);
		}
	}

	private function updateSystem(): void
	{
		$this->constructSystemForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$system = $this->form->exportValues();
			SystemEntity::update($this->dbh, $system, $this->keyFields['systemId']->exportValue());
			header("Location: ".$_SERVER["SCRIPT_NAME"]."/systems/".$system['SYSTEM_ID']);
			exit();
		}
	}

	private function deleteSystem(): void
	{
		SystemEntity::remove($this->dbh, $this->keyFields['systemId']->exportValue());
		header("Location: ".$_SERVER['HTTP_REFERER'].AnchorRow::composePreviousRowFragment());
		exit();
	}

	public function executeOperation(): void
	{
		if(array_key_exists("__operation", $_REQUEST))
		{
			switch($_REQUEST["__operation"])
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
				default:
					$this->viewSystem();
			}
		}
		else
			$this->viewSystem();
	}
}
?>
