<?php
namespace SBExampleApps\Users\Model\CRUD;
use Exception;
use PDO;
use SBLayout\Model\Route;
use SBData\Model\Form;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\KeyLinkField;
use SBData\Model\Field\PasswordField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBData\Model\Value\Value;
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

	public ?Form $addSystemForm = null;

	public ?DBTable $table = null;

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
			"__operation" => new HiddenField(true),
			"Username" => new TextField("Username", true, 20, 255),
			"Password" => new PasswordField("Password", $passwordMandatory, 20, 255),
			"FullName" => new TextField("Full name", true, 20, 255)
		));
	}

	private function constructAddSystemForm(): void
	{
		$this->addSystemForm = new CRUDForm(array(
			"__operation" => new HiddenField(true),
			"SYSTEM_ID" => new DBComboBoxField("System", $this->dbh, "SBExampleApps\\Users\\Model\\Entity\\SystemEntity::queryAll", "SBExampleApps\\Users\\Model\\Entity\\SystemEntity::queryOne", true)
		));

		$this->addSystemForm->setOperation("insert_user_system");
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
			header("Location: ".$_SERVER["PHP_SELF"]."/".rawurlencode($user["Username"]));
			exit();
		}
	}

	private function viewUserProperties(): void
	{
		/* Query the properties of the requested user and construct a form from it */
		$this->constructUserForm(false);
		$this->form->setOperation("update_user");
		$this->form->importValues($this->crudPage->entity);

		/* Construct a table containing the systems for this form */
		$composeSystemLink = function (KeyLinkField $field, Form $form): string
		{
			$systemId = $field->exportValue();
			return $_SERVER["SCRIPT_NAME"]."/systems/".$systemId;
		};

		$deleteUserSystemLink = function (Form $form): string
		{
			$systemId = $form->fields["SYSTEM_ID"]->exportValue();
			return $_SERVER['PHP_SELF']."?".http_build_query(array(
				"__operation" => "delete_user_system",
				"SYSTEM_ID" => $systemId
			), "", "&amp;", PHP_QUERY_RFC3986).AnchorRow::composeRowParameter($form);
		};

		$this->table = new DBTable(array(
			"SYSTEM_ID" => new KeyLinkField("Id", $composeSystemLink, true),
			"Description" => new TextField("Description", true)
		), array(
			"Delete" => $deleteUserSystemLink
		));

		$this->table->stmt = UserEntity::queryAllAuthorizedSystems($this->dbh, $GLOBALS["query"]["Username"]);
	}

	private function viewUser(): void
	{
		$this->viewUserProperties();
		/* Construct a form that can be used to add systems */
		$this->constructAddSystemForm();
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

	private function insertAuthorizedSystem(): void
	{
		$this->constructAddSystemForm();
		$this->addSystemForm->importValues($_REQUEST);
		$this->addSystemForm->checkFields();

		if($this->addSystemForm->checkValid())
		{
			UserEntity::insertAuthorizedSystem($this->dbh, $GLOBALS["query"]["Username"], $this->addSystemForm->fields["SYSTEM_ID"]->exportValue());
			header("Location: ".$_SERVER["PHP_SELF"]."#systems");
			exit();
		}
		else
			$this->viewUserProperties();
	}

	private function deleteAuthorizedSystem(): void
	{
		$systemIdValue = new Value(true, 255);
		$systemIdValue->value = $_REQUEST["SYSTEM_ID"];

		if($systemIdValue->checkValue("SYSTEM_ID"))
		{
			UserEntity::removeAuthorizedSystem($this->dbh, $GLOBALS["query"]["Username"], $systemIdValue->value);
			header("Location: ".$_SERVER["PHP_SELF"].AnchorRow::composePreviousRowFragment("user-system-row"));
			exit();
		}
		else
			throw new Exception("Invalid system id!");
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
				case "insert_user_system":
					$this->insertAuthorizedSystem();
					break;
				case "delete_user_system":
					$this->deleteAuthorizedSystem();
					break;
				default:
					$this->viewUser();
			}
		}
	}
}
?>
