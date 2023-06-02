<?php
namespace SBExampleApps\Literature\Model\CRUD;
use Exception;
use PDO;
use SBLayout\Model\Route;
use SBLayout\Model\PageForbiddenException;
use SBData\Model\Form;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\OperationParamPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\Entity\PublisherEntity;

class PublisherCRUDInterface extends CRUDInterface
{
	public OperationParamPage $operationParamPage;

	public PDO $dbh;

	public CRUDForm $form;

	public AuthorizationManager $authorizationManager;

	public function __construct(Route $route, OperationParamPage $operationParamPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($operationParamPage);
		$this->route = $route;
		$this->operationParamPage = $operationParamPage;
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructPublisherForm(): void
	{
		$this->form = new CRUDForm(array(
			 "PUBLISHER_ID" => new TextField("Id", true, 20, 255),
			"Name" => new TextField("Name", true, 20, 255)
		));
	}

	private function createPublisher(): void
	{
		$this->constructPublisherForm();
		$this->form->setOperation("insert_publisher");
	}

	private function insertPublisher(): void
	{
		$this->constructPublisherForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$publisher = $this->form->exportValues();
			PublisherEntity::insert($this->dbh, $publisher);
			header("Location: ".RouteUtils::composeSelfURL()."/".rawurlencode($publisher['PUBLISHER_ID']));
			exit();
		}
	}

	private function viewPublisher(): void
	{
		/* Query the properties of the requested publisher and construct a form from it */
		$this->constructPublisherForm();
		$this->form->importValues($this->operationParamPage->entity);
		$this->form->setOperation("update_publisher");
	}

	private function updatePublisher(): void
	{
		$this->constructPublisherForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$publisher = $this->form->exportValues();
			PublisherEntity::update($this->dbh, $publisher, $GLOBALS["query"]["publisherId"]);
			header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"])."/".rawurlencode($publisher['PUBLISHER_ID']));
			exit();
		}
	}

	private function deletePublisher(): void
	{
		PublisherEntity::remove($this->dbh, $GLOBALS["query"]["publisherId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composePreviousRowFragment());
		exit();
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewPublisher();
		else
		{
			if($this->authorizationManager->authenticated) // Write operations are only allowed when authenticated
			{
				switch($operation)
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
				}
			}
			else
				throw new PageForbiddenException("No permissions to modify a publisher!");
		}
	}
}
?>
