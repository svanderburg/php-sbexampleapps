<?php
namespace SBExampleApps\Users\Model\CRUD;
use PDO;
use SBLayout\Model\Route;
use SBData\Model\Form;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\PasswordField;
use SBData\Model\Field\TextField;
use SBData\Model\Value\Value;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\CRUDPage;
use SBExampleApps\Users\Model\Entity\UserEntity;

class UserCRUDInterface extends CRUDInterface
{
	public CRUDPage $crudPage;

	public Route $route;

	public PDO $dbh;

	public CRUDForm $form;

	public function __construct(Route $route, CRUDPage $crudPage, PDO $dbh)
	{
		parent::__construct($crudPage);
		$this->route = $route;
		$this->crudPage = $crudPage;
		$this->dbh = $dbh;
	}

	private function constructUserForm(bool $passwordMandatory): void
	{
		$this->form = new CRUDForm(array(
			"Username" => new TextField("Username", true, 20, 255),
			"Password" => new PasswordField("Password", $passwordMandatory, 20, 255),
			"FullName" => new TextField("Full name", true, 20, 255)
		));
	}

	private function createUser(): void
	{
		$this->constructUserForm(true);
		$this->form->setOperation("insert_user");
	}

	private function insertUser(): void
	{
		$this->constructUserForm(true);
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$user = $this->form->exportValues();
			UserEntity::insert($this->dbh, $user);
			header("Location: ".RouteUtils::composeSelfURL()."/".rawurlencode($user["Username"]));
			exit();
		}
	}

	private function viewUserProperties(): void
	{
		/* Query the properties of the requested user and construct a form from it */
		$this->constructUserForm(false);
		$this->form->setOperation("update_user");
		$this->form->importValues($this->crudPage->entity);
	}

	private function viewUser(): void
	{
		$this->viewUserProperties();
		/* Construct a form that can be used to add systems */
	}

	private function updateUser(): void
	{
		$this->constructUserForm(false);
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$user = $this->form->exportValues();
			UserEntity::update($this->dbh, $user, $GLOBALS["query"]["Username"]);
			header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"])."/".$user['Username']);
			exit();
		}
	}

	private function deleteUser(): void
	{
		UserEntity::remove($this->dbh, $GLOBALS["query"]["Username"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composePreviousRowFragment());
		exit();
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewUser();
		else
		{
			switch($operation)
			{
				case "create_user":
					$this->createUser();
					break;
				case "insert_user":
					$this->insertUser();
					break;
				case "update_user":
					$this->updateUser();
					break;
				case "delete_user":
					$this->deleteUser();
					break;
				default:
					$this->viewUser();
			}
		}
	}
}
?>
