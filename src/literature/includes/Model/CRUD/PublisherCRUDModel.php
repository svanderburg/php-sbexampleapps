<?php
namespace SBExampleApps\Literature\Model\CRUD;
use Exception;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\Entity\PublisherEntity;

class PublisherCRUDModel extends CRUDModel
{
	public $dbh;

	public $form = null;

	public $authorizationManager;

	public function __construct(CRUDPage $crudPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructPublisherForm()
	{
		$this->form = new Form(array(
			"__operation" => new HiddenField(true),
			 "PUBLISHER_ID" => new TextField("Id", true, 20, 255),
			"Name" => new TextField("Name", true, 20, 255)
		));
	}

	private function createPublisher()
	{
		$this->constructPublisherForm();

		$row = array(
			"__operation" => "insert_publisher"
		);
		$this->form->importValues($row);
	}

	private function insertPublisher()
	{
		$this->constructPublisherForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$publisher = $this->form->exportValues();
			PublisherEntity::insert($this->dbh, $publisher);
			header("Location: ".$_SERVER["SCRIPT_NAME"]."/publishers/".$publisher['PUBLISHER_ID']);
			exit();
		}
	}

	private function viewPublisher()
	{
		/* Query the properties of the requested publisher and construct a form from it */
		$this->constructPublisherForm();

		$stmt = PublisherEntity::queryOne($this->dbh, $this->keyFields['publisherId']->value);

		if(($row = $stmt->fetch()) === false)
		{
			header("HTTP/1.1 404 Not Found");
			throw new Exception("Cannot find publisher with this id!");
		}
		else
		{
			$row['__operation'] = "update_publisher";
			$this->form->importValues($row);
		}
	}

	private function updatePublisher()
	{
		$this->constructPublisherForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$publisher = $this->form->exportValues();
			PublisherEntity::update($this->dbh, $publisher, $this->keyFields['publisherId']->value);
			header("Location: ".$_SERVER["SCRIPT_NAME"]."/publishers/".$publisher['PUBLISHER_ID']);
			exit();
		}
	}

	private function deletePublisher()
	{
		PublisherEntity::remove($this->dbh, $this->keyFields['publisherId']->value);
		header("Location: ".$_SERVER['HTTP_REFERER'].AnchorRow::composeRowFragment());
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
					case "create_publisher":
						$this->createPublisher();
						break;
					case "insert_publisher":
						$this->insertPublisher();
						break;
					case "update_publisher":
						$this->updatePublisher();
						break;
					case "delete_publisher":
						$this->deletePublisher();
						break;
					default:
						$this->viewPublisher();
				}
			}
			else
			{
				header("HTTP/1.1 403 Forbidden");
				throw new Exception("No permissions to modify a publisher!");
			}
		}
		else
			$this->viewPublisher();
	}
}
?>
