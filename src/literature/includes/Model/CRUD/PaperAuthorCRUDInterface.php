<?php
namespace SBExampleApps\Literature\Model\CRUD;
use PDO;
use SBLayout\Model\Route;
use SBLayout\Model\BadRequestException;
use SBLayout\Model\PageForbiddenException;
use SBData\Model\Form;
use SBData\Model\Value\NaturalNumberValue;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBData\Model\Field\NaturalNumberKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBCrud\Model\RouteUtils;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\CRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\Entity\PaperEntity;

class PaperAuthorCRUDInterface extends CRUDInterface
{
	public CRUDPage $crudPage;

	public PDO $dbh;

	public Form $addAuthorForm;

	public DBTable $table;

	public function __construct(Route $route, CRUDPage $crudPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($crudPage);
		$this->route = $route;
		$this->crudPage = $crudPage;
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructAddAuthorForm(): void
	{
		$this->addAuthorForm = new CRUDForm(array(
			"AUTHOR_ID" => new DBComboBoxField("Author", $this->dbh, "SBExampleApps\\Literature\\Model\\Entity\\AuthorEntity::querySummary", "SBExampleApps\\Literature\\Model\\Entity\\AuthorEntity::queryOneSummary", true)
		));

		$this->addAuthorForm->setOperation("insert_paper_author");
	}

	private function constructTable(): void
	{
		$composeAuthorLink = function (NaturalNumberKeyLinkField $field, Form $form): string
		{
			$authorId = $field->exportValue();
			return $_SERVER["SCRIPT_NAME"]."/authors/".rawurlencode($authorId);
		};

		$deletePaperAuthorLink = function (Form $form): string
		{
			$authorId = $form->fields["AUTHOR_ID"]->exportValue();
			return RouteUtils::composeSelfURL()."?".http_build_query(array(
				"__operation" => "delete_paper_author",
				"AUTHOR_ID" => $authorId
			), "", "&amp;", PHP_QUERY_RFC3986).AnchorRow::composeRowParameter($form);
		};

		$this->table = new DBTable(array(
			"AUTHOR_ID" => new NaturalNumberKeyLinkField("Id", $composeAuthorLink, true),
			"LastName" => new TextField("Last name", true),
			"FirstName" => new TextField("First name", true)
		), array(
			"Delete" => $deletePaperAuthorLink
		));

		$this->table->stmt = PaperEntity::queryAuthors($this->dbh, $GLOBALS["query"]["paperId"], $GLOBALS["query"]["conferenceId"]);
	}

	private function viewAuthors(): void
	{
		$this->constructAddAuthorForm();
		$this->constructTable();
	}

	private function insertPaperAuthor(): void
	{
		$this->constructAddAuthorForm();
		$this->constructTable();
		$this->addAuthorForm->importValues($_REQUEST);
		$this->addAuthorForm->checkFields();

		if($this->addAuthorForm->checkValid())
		{
			PaperEntity::insertAuthor($this->dbh, $GLOBALS["query"]["paperId"], $GLOBALS["query"]["conferenceId"], $this->addAuthorForm->fields["AUTHOR_ID"]->exportValue());
			header("Location: ".RouteUtils::composeSelfURL());
			exit();
		}
		else
			$this->viewAuthors();
	}

	private function deletePaperAuthor(): void
	{
		$authorIdValue = new NaturalNumberValue(true);
		$authorIdValue->value = $_REQUEST["AUTHOR_ID"];

		if($authorIdValue->checkValue("AUTHOR_ID"))
		{
			PaperEntity::removeAuthor($this->dbh, $GLOBALS["query"]["paperId"], $GLOBALS["query"]["conferenceId"], $authorIdValue->value);
			header("Location: ".RouteUtils::composeSelfURL().AnchorRow::composePreviousRowFragment());
			exit();
		}
		else
			throw new BadRequestException("Invalid author id!");
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewAuthors();
		else
		{
			if($this->authorizationManager->authenticated) // Write operations are only allowed when authenticated
			{
				switch($operation)
				{
					case "insert_paper_author":
						$this->insertPaperAuthor();
						break;
					case "delete_paper_author":
						$this->deletePaperAuthor();
						break;
				}
			}
			else
				throw new PageForbiddenException("No permissions to modify a paper!");
		}
	}
}
?>
