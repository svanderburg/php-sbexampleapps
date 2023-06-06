<?php
namespace SBExampleApps\Users\Model\CRUD;
use PDO;
use SBLayout\Model\Route;
use SBLayout\Model\BadRequestException;
use SBData\Model\Table\Anchor\AnchorRow;
use SBData\Model\Value\Value;
use SBCrud\Model\RouteUtils;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\OperationParamPage;
use SBExampleApps\Users\Model\Entity\UserEntity;

class AuthorizedSystemCRUDInterface extends CRUDInterface
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

	private function redirectToSystemPage(): void
	{
		header("Location: ".$_SERVER["SCRIPT_NAME"]."/systems/".rawurlencode($GLOBALS["query"]["systemId"]));
		exit();
	}

	private function insertAuthorizedSystem(): void
	{
		$systemIdValue = new Value(true, 255);
		$systemIdValue->value = $_REQUEST["SYSTEM_ID"];

		if($systemIdValue->checkValue("SYSTEM_ID"))
		{
			UserEntity::insertAuthorizedSystem($this->dbh, $GLOBALS["query"]["Username"], $systemIdValue->value);
			header("Location: ".RouteUtils::composeSelfURL());
			exit();
		}
		else
			throw new BadRequestException("Invalid system ID: ".$systemIdValue->value);
	}

	private function deleteAuthorizedSystem(): void
	{
		UserEntity::removeAuthorizedSystem($this->dbh, $GLOBALS["query"]["Username"], $GLOBALS["query"]["systemId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composePreviousRowFragment());
		exit();
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->redirectToSystemPage();
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