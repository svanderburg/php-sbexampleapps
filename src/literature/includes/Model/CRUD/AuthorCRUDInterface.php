<?php
namespace SBExampleApps\Literature\Model\CRUD;
use Exception;
use PDO;
use SBLayout\Model\Route;
use SBLayout\Model\PageForbiddenException;
use SBData\Model\Form;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\ReadOnlyNumericIntTextField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\URLField;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\CRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\Entity\AuthorEntity;

class AuthorCRUDInterface extends CRUDInterface
{
	public Route $route;

	public CRUDPage $currentPage;

	public PDO $dbh;

	public CRUDForm $form;

	public AuthorizationManager $authorizationManager;

	public function __construct(Route $route, CRUDPage $crudPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($crudPage);
		$this->route = $route;
		$this->crudPage = $crudPage;
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructAuthorForm(): void
	{
		$this->form = new CRUDForm(array(
			"__operation" => new HiddenField(true),
			"AUTHOR_ID" => new ReadOnlyNumericIntTextField("Id", false),
			"FirstName" => new TextField("First name", true, 20, 255),
			"LastName" => new TextField("Last name", true, 20, 255),
			"Homepage" => new URLField("Homepage", false),
		));
	}

	private function createAuthor(): void
	{
		$this->constructAuthorForm();
		$this->form->setOperation("insert_author");
	}

	private function insertAuthor(): void
	{
		$this->constructAuthorForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$author = $this->form->exportValues();
			$authorId = AuthorEntity::insert($this->dbh, $author);
			header("Location: ".$_SERVER["PHP_SELF"]."/".rawurlencode($authorId));
			exit();
		}
	}

	private function viewAuthor(): void
	{
		/* Query the properties of the requested author and construct a form from it */
		$this->constructAuthorForm();
		$this->form->importValues($this->crudPage->entity);
		$this->form->setOperation("update_author");
	}

	private function updateAuthor(): void
	{
		$this->constructAuthorForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$authorId = $GLOBALS["query"]["authorId"];
			$author = $this->form->exportValues();

			AuthorEntity::update($this->dbh, $author, $authorId);
			header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"])."/".rawurlencode($authorId));
			exit();
		}
	}

	private function deleteAuthor(): void
	{
		AuthorEntity::remove($this->dbh, $GLOBALS["query"]["authorId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composePreviousRowFragment());
		exit();
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewAuthor();
		else
		{
			if($this->authorizationManager->authenticated) // Write operations are only allowed when authenticated
			{
				switch($operation)
				{
					case "create_author":
						$this->createAuthor();
						break;
					case "insert_author":
						$this->insertAuthor();
						break;
					case "update_author":
						$this->updateAuthor();
						break;
					case "delete_author":
						$this->deleteAuthor();
						break;
				}
			}
			else
				throw new PageForbiddenException("No permissions to modify an author!");
		}
	}
}
?>
