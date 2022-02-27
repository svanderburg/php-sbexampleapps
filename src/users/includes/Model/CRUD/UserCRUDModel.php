<?php
namespace SBExampleApps\Users\Model\CRUD;
use Exception;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\KeyLinkField;
use SBData\Model\Field\PasswordField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBData\Model\Table\DBTable;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Users\Model\Entity\SystemEntity;
use SBExampleApps\Users\Model\Entity\UserEntity;

class UserCRUDModel extends CRUDModel
{
	public PDO $dbh;

	public ?Form $form = null;

	public ?Form $addSystemForm = null;

	public ?DBTable $table = null;

	public function __construct(CRUDPage $crudPage, PDO $dbh)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
	}

	private function constructUserForm(bool $passwordMandatory): void
	{
		$this->form = new Form(array(
			"__operation" => new HiddenField(true),
			"Username" => new TextField("Username", true, 20, 255),
			"Password" => new PasswordField("Password", $passwordMandatory, 20, 255),
			"FullName" => new TextField("Full name", true, 20, 255)
		));
	}

	private function constructAddSystemForm(): void
	{
		$stmt = SystemEntity::queryAll($this->dbh);

		$this->addSystemForm = new Form(array(
			"__operation" => new HiddenField(true),
			"SYSTEM_ID" => new DBComboBoxField("System", $stmt, true)
		));

		$this->addSystemForm->fields["__operation"]->value = "insert_user_system";
	}

	private function createUser(): void
	{
		$this->constructUserForm(true);

		$row = array(
			"__operation" => "insert_user"
		);
		$this->form->importValues($row);
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
			header("Location: ".$_SERVER["SCRIPT_NAME"]."/users/".$user['Username']);
			exit();
		}
	}

	private function viewUserProperties(): void
	{
		/* Query the properties of the requested user and construct a form from it */
		$this->constructUserForm(false);

		$stmt = UserEntity::queryOne($this->dbh, $this->keyFields['Username']->value);

		if(($row = $stmt->fetch()) === false)
		{
			header("HTTP/1.1 404 Not Found");
			throw new Exception("Cannot find user with this username!");
		}
		else
		{
			$row['__operation'] = "update_user";
			$this->form->importValues($row);

			/* Construct a table containing the systems for this form */
			function composeSystemLink(KeyLinkField $field, Form $form): string
			{
				return $_SERVER["SCRIPT_NAME"]."/systems/".$field->value;
			}

			function deleteUserSystemLink(Form $form): string
			{
				return $_SERVER['PHP_SELF']."?__operation=delete_user_system&amp;SYSTEM_ID=".$form->fields["SYSTEM_ID"]->value;
			}

			$this->table = new DBTable(array(
				"SYSTEM_ID" => new KeyLinkField("Id", __NAMESPACE__.'\\composeSystemLink', true),
				"Description" => new TextField("Description", true)
			), array(
				"Delete" => __NAMESPACE__.'\\deleteUserSystemLink'
			));

			$this->table->stmt = UserEntity::queryAllAuthorizedSystems($this->dbh, $this->keyFields['Username']->value);
		}
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
			UserEntity::update($this->dbh, $user, $this->keyFields['Username']->value);
			header("Location: ".$_SERVER["SCRIPT_NAME"]."/users/".$user['Username']);
			exit();
		}
	}

	private function deleteUser(): void
	{
		UserEntity::remove($this->dbh, $this->keyFields['Username']->value);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	private function insertAuthorizedSystem(): void
	{
		$this->constructAddSystemForm();
		$this->addSystemForm->importValues($_REQUEST);
		$this->addSystemForm->checkFields();

		if($this->addSystemForm->checkValid())
		{
			UserEntity::insertAuthorizedSystem($this->dbh, $this->keyFields['Username']->value, $this->addSystemForm->fields["SYSTEM_ID"]->value);
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit();
		}
		else
			$this->viewUserProperties();
	}

	private function deleteAuthorizedSystem(): void
	{
		$systemIdField = new TextField("Id", true);
		$systemIdField->value = $_REQUEST["SYSTEM_ID"];

		if($systemIdField->checkField("SYSTEM_ID"))
		{
			UserEntity::removeAuthorizedSystem($this->dbh, $this->keyFields['Username']->value, $systemIdField->value);
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit();
		}
		else
			throw new Exception("Invalid system id!");
	}

	public function executeOperation(): void
	{
		if(array_key_exists("__operation", $_REQUEST))
		{
			switch($_REQUEST["__operation"])
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
		else
			$this->viewUser();
	}
}
?>
