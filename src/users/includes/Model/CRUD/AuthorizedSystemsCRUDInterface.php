<?php
namespace SBExampleApps\Users\Model\CRUD;
use PDO;
use SBLayout\Model\Route;
use SBLayout\Model\BadRequestException;
use SBData\Model\Form;
use SBData\Model\ReadOnlyForm;
use SBData\Model\Table\Action;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBData\Model\Value\Value;
use SBData\Model\Field\KeyLinkField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBCrud\Model\RouteUtils;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\OperationParamPage;
use SBExampleApps\Users\Model\Entity\UserEntity;

class AuthorizedSystemsCRUDInterface extends CRUDInterface
{
	public OperationParamPage $operationParamPage;

	public Route $route;

	public PDO $dbh;

	public Form $addSystemForm;

	public DBTable $table;

	public function __construct(Route $route, OperationParamPage $operationParamPage, PDO $dbh)
	{
		parent::__construct($operationParamPage);
		$this->route = $route;
		$this->operationParamPage = $operationParamPage;
		$this->dbh = $dbh;
	}

	private function constructAddSystemForm(): void
	{
		$this->addSystemForm = new CRUDForm(array(
			"SYSTEM_ID" => new DBComboBoxField("System", $this->dbh, "SBExampleApps\\Users\\Model\\Entity\\SystemEntity::queryAll", "SBExampleApps\\Users\\Model\\Entity\\SystemEntity::queryOne", true)
		));

		$this->addSystemForm->setOperation("insert_user_system");
	}

	private function constructTable(): void
	{
		$composeSystemLink = function (KeyLinkField $field, ReadOnlyForm $form): string
		{
			$systemId = $field->exportValue();
			return $_SERVER["SCRIPT_NAME"]."/systems/".rawurlencode($systemId);
		};

		$deleteUserSystemLink = function (ReadOnlyForm $form): string
		{
			$systemId = $form->fields["SYSTEM_ID"]->exportValue();
			return RouteUtils::composeSelfURL()."?".http_build_query(array(
				"__operation" => "delete_user_system",
				"SYSTEM_ID" => $systemId
			), "", "&amp;", PHP_QUERY_RFC3986).AnchorRow::composeRowParameter($form);
		};

		$this->table = new DBTable(array(
			"SYSTEM_ID" => new KeyLinkField("Id", $composeSystemLink, true),
			"Description" => new TextField("Description", true)
		), array(
			"Delete" => new Action($deleteUserSystemLink)
		));

		$this->table->setStatement(UserEntity::queryAllAuthorizedSystems($this->dbh, $GLOBALS["query"]["Username"]));
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
			header("Location: ".RouteUtils::composeSelfURL());
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
			header("Location: ".RouteUtils::composeSelfURL().AnchorRow::composePreviousRowFragment());
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