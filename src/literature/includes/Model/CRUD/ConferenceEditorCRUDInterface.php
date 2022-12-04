<?php
namespace SBExampleApps\Literature\Model\CRUD;
use PDO;
use SBLayout\Model\Route;
use SBLayout\Model\BadRequestException;
use SBLayout\Model\PageForbiddenException;
use SBData\Model\Value\NaturalNumberValue;
use SBData\Model\Form;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBData\Model\Field\NumericIntKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\CRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\Entity\ConferenceEntity;

class ConferenceEditorCRUDInterface extends CRUDInterface
{
	public CRUDPage $crudPage;

	public PDO $dbh;

	public CRUDForm $form;

	public CRUDForm $addEditorForm;

	public DBTable $editorsTable;

	public function __construct(Route $route, CRUDPage $crudPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($crudPage);
		$this->route = $route;
		$this->crudPage = $crudPage;
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
		$composeAuthorLink = function (NumericIntKeyLinkField $field, Form $form): string
		{
			$authorId = $field->exportValue();
			return $_SERVER["SCRIPT_NAME"]."/authors/".rawurlencode($authorId);
		};

		$deleteConferenceAuthorLink = function (Form $form): string
		{
			$authorId = $form->fields["AUTHOR_ID"]->exportValue();
			return $_SERVER["PHP_SELF"]."?".http_build_query(array(
				"AUTHOR_ID" => $authorId,
				"__operation" => "delete_conference_author"
			), "", "&amp;", PHP_QUERY_RFC3986).AnchorRow::composeRowParameter($form);
		};

		$this->table = new DBTable(array(
			"AUTHOR_ID" => new NumericIntKeyLinkField("Id", $composeAuthorLink, true),
			"LastName" => new TextField("Last name", true, 20, 255),
			"FirstName" => new TextField("First name", true, 20, 255)
		), array(
			"Delete" => $deleteConferenceAuthorLink
		));

		$this->table->stmt = ConferenceEntity::queryEditors($this->dbh, $GLOBALS["query"]["conferenceId"]);
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
			header("Location: ".$_SERVER["PHP_SELF"]);
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
			header("Location: ".$_SERVER["PHP_SELF"].AnchorRow::composePreviousRowFragment());
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
