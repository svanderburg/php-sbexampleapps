<?php
namespace SBExampleApps\Literature\Model\CRUD;
use PDO;
use SBLayout\Model\Route;
use SBLayout\Model\BadRequestException;
use SBLayout\Model\PageForbiddenException;
use SBData\Model\Value\NaturalNumberValue;
use SBData\Model\Form;
use SBData\Model\ReadOnlyForm;
use SBData\Model\Table\Action;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBData\Model\Field\NaturalNumberKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBCrud\Model\RouteUtils;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\OperationParamPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\Entity\ConferenceEntity;

class ConferenceEditorCRUDInterface extends CRUDInterface
{
	public OperationParamPage $operationParamPage;

	public PDO $dbh;

	public CRUDForm $form;

	public CRUDForm $addEditorForm;

	public DBTable $editorsTable;

	public function __construct(Route $route, OperationParamPage $operationParamPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($operationParamPage);
		$this->route = $route;
		$this->operationParamPage = $operationParamPage;
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructAddEditorForm(): void
	{
		$this->addEditorForm = new CRUDForm(array(
			"AUTHOR_ID" => new DBComboBoxField("Editor", $this->dbh, "SBExampleApps\\Literature\\Model\\Entity\\AuthorEntity::querySummary", "SBExampleApps\\Literature\\Model\\Entity\\AuthorEntity::queryOneSummary", true)
		));
		$this->addEditorForm->setOperation("insert_conference_author");
	}

	private function constructTable(): void
	{
		$composeAuthorLink = function (NaturalNumberKeyLinkField $field, ReadOnlyForm $form): string
		{
			$authorId = $field->exportValue();
			return $_SERVER["SCRIPT_NAME"]."/authors/".rawurlencode($authorId);
		};

		$deleteConferenceAuthorLink = function (ReadOnlyForm $form): string
		{
			$authorId = $form->fields["AUTHOR_ID"]->exportValue();
			return RouteUtils::composeSelfURL()."?".http_build_query(array(
				"AUTHOR_ID" => $authorId,
				"__operation" => "delete_conference_author"
			), "", "&amp;", PHP_QUERY_RFC3986).AnchorRow::composeRowParameter($form);
		};

		$this->table = new DBTable(array(
			"AUTHOR_ID" => new NaturalNumberKeyLinkField("Id", $composeAuthorLink, true),
			"LastName" => new TextField("Last name", true, 20, 255),
			"FirstName" => new TextField("First name", true, 20, 255)
		), array(
			"Delete" => new Action($deleteConferenceAuthorLink)
		));

		$this->table->setStatement(ConferenceEntity::queryEditors($this->dbh, $GLOBALS["query"]["conferenceId"]));
	}

	private function viewEditors(): void
	{
		$this->constructAddEditorForm();
		$this->constructTable();
	}

	private function insertConferenceEditor(): void
	{
		$this->constructAddEditorForm();
		$this->addEditorForm->importValues($_REQUEST);
		$this->addEditorForm->checkFields();

		if($this->addEditorForm->checkValid())
		{
			ConferenceEntity::insertEditor($this->dbh, $GLOBALS["query"]["conferenceId"], $this->addEditorForm->fields["AUTHOR_ID"]->exportValue());
			header("Location: ".RouteUtils::composeSelfURL());
			exit();
		}
		else
			$this->viewEditors();
	}

	private function deleteConferenceEditor(): void
	{
		$authorIdValue = new NaturalNumberValue(true);
		$authorIdValue->value = $_REQUEST["AUTHOR_ID"];

		if($authorIdValue->checkValue("AUTHOR_ID"))
		{
			ConferenceEntity::removeEditor($this->dbh, $GLOBALS["query"]["conferenceId"], $authorIdValue->value);
			header("Location: ".RouteUtils::composeSelfURL().AnchorRow::composePreviousRowFragment());
			exit();
		}
		else
			throw new BadRequestException("Invalid author id: ".$authorIdValue->value);
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewEditors();
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
