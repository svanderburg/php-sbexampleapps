<?php
namespace SBExampleApps\Literature\Model\CRUD;
use PDO;
use SBLayout\Model\Route;
use SBLayout\Model\BadRequestException;
use SBLayout\Model\PageForbiddenException;
use SBData\Model\Value\NaturalNumberValue;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\OperationParamPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\Entity\ConferenceEntity;

class ConferenceEditorCRUDInterface extends CRUDInterface
{
	public OperationParamPage $operationParamPage;

	public PDO $dbh;

	public function __construct(Route $route, OperationParamPage $operationParamPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($operationParamPage);
		$this->route = $route;
		$this->operationParamPage = $operationParamPage;
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function redirectToAuthorPage(): void
	{
		header("Location: ".$_SERVER["SCRIPT_NAME"]."/authors/".rawurlencode($GLOBALS["query"]["authorId"]));
		exit();
	}

	private function insertConferenceEditor(): void
	{
		$authorIdValue = new NaturalNumberValue();
		$authorIdValue->value = $_REQUEST["AUTHOR_ID"];

		if($authorIdValue->checkValue("AUTHOR_ID"))
		{
			ConferenceEntity::insertEditor($this->dbh, $GLOBALS["query"]["conferenceId"], $authorIdValue->value);
			header("Location: ".RouteUtils::composeSelfURL());
			exit();
		}
		else
			throw new BadRequestException("Invalid author ID: ".$authorIdValue->value);
	}

	private function deleteConferenceEditor(): void
	{
		ConferenceEntity::removeEditor($this->dbh, $GLOBALS["query"]["conferenceId"], $GLOBALS["query"]["authorId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composePreviousRowFragment());
		exit();
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->redirectToAuthorPage();
		else
		{
			if($this->authorizationManager->authenticated) // Write operations are only allowed when authenticated
			{
				switch($operation)
				{
					case "insert_conference_author":
						$this->insertConferenceEditor();
						break;
					case "delete_conference_author":
						$this->deleteConferenceEditor();
						break;
				}
			}
			else
				throw new PageForbiddenException("No permissions to modify a conference!");
		}
	}
}
?>
