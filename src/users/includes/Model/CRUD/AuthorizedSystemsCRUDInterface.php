<?php
namespace SBExampleApps\Users\Model\CRUD;
use PDO;
use SBLayout\Model\Route;
use SBLayout\Model\BadRequestException;
use SBData\Model\Form;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBData\Model\Value\Value;
use SBData\Model\Field\KeyLinkField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\CRUDPage;
use SBExampleApps\Users\Model\Entity\UserEntity;

class AuthorizedSystemsCRUDInterface extends CRUDInterface
{
	public CRUDPage $crudPage;

	public Route $route;

	public PDO $dbh;

	public Form $addSystemForm;

	public DBTable $table;

	public function __construct(Route $route, CRUDPage $crudPage, PDO $dbh)
	{
		parent::__construct($crudPage);
		$this->route = $route;
		$this->crudPage = $crudPage;
		$this->dbh = $dbh;
	}

	private function constructAddSystemForm(): void
	{
		$this->addSystemForm = new CRUDForm(array(
			"__operation" => new HiddenField(true),
			"SYSTEM_ID" => new DBComboBoxField("System", $this->dbh, "SBExampleApps\\Users\\Model\\Entity\\SystemEntity::queryAll", "SBExampleApps\\Users\\Model\\Entity\\SystemEntity::queryOne", true)
		));

		$this->addSystemForm->setOperation("insert_user_system");
	}

	private function constructTable(): void
	{
		$composeSystemLink = function (KeyLinkField $field, Form $form): string
		{
			$systemId = $field->exportValue();
			return $_SERVER["SCRIPT_NAME"]."/systems/".rawurlencode($systemId);
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

	private function viewAuthorizedSystems(): void
	{
		$this->constructAddSystemForm();
		$this->constructTable();
	}

	private function insertAuthorizedSystem(): void
	{
		$this->constructAddSystemForm();
		$this->addSystemForm->importValues($_REQUEST);
		$this->addSystemForm->checkFields();

		if($this->addSystemForm->checkValid())
		{
			UserEntity::insertAuthorizedSystem($this->dbh, $GLOBALS["query"]["Username"], $this->addSystemForm->fields["SYSTEM_ID"]->exportValue());
			header("Location: ".$_SERVER["PHP_SELF"]);
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
			throw new BadRequestException("Invalid system id!");
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewAuthorizedSystems();
		else
		{
			switch($operation)
			{
				case "insert_user_system":
					$this->insertAuthorizedSystem();
					break;
				case "delete_user_system":
					$this->deleteAuthorizedSystem();
					break;
			}
		}
	}
}
?>