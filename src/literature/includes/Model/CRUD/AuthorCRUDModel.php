<?php
namespace SBExampleApps\Literature\Model\CRUD;
use Exception;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\ReadOnlyNumericIntTextField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\URLField;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\Entity\AuthorEntity;

class AuthorCRUDModel extends CRUDModel
{
	public $dbh;

	public $form = null;

	public $authorizationManager;

	public function __construct(CRUDPage $crudPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructAuthorForm()
	{
		$this->form = new Form(array(
			"__operation" => new HiddenField(true),
			"AUTHOR_ID" => new ReadOnlyNumericIntTextField("Id", false),
			"FirstName" => new TextField("First name", true, 20, 255),
			"LastName" => new TextField("Last name", true, 20, 255),
			"Homepage" => new URLField("Homepage", false),
		));
	}

	private function createAuthor()
	{
		$this->constructAuthorForm();

		$row = array(
			"__operation" => "insert_author"
		);
		$this->form->importValues($row);
	}

	private function insertAuthor()
	{
		$this->constructAuthorForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$author = $this->form->exportValues();
			$authorId = AuthorEntity::insert($this->dbh, $author);
			header("Location: ".$_SERVER["SCRIPT_NAME"]."/authors/".$authorId);
			exit();
		}
	}

	private function viewAuthor()
	{
		/* Query the properties of the requested author and construct a form from it */
		$this->constructAuthorForm();

		$stmt = AuthorEntity::queryOne($this->dbh, $this->keyFields['authorId']->value);

		if(($row = $stmt->fetch()) === false)
		{
			header("HTTP/1.1 404 Not Found");
			throw new Exception("Cannot find author with this id!");
		}
		else
		{
			$row['__operation'] = "update_author";
			$this->form->importValues($row);
		}
	}

	private function updateAuthor()
	{
		$this->constructAuthorForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$author = $this->form->exportValues();
			AuthorEntity::update($this->dbh, $author, $this->keyFields['authorId']->value);
			header("Location: ".$_SERVER["SCRIPT_NAME"]."/authors/".$this->keyFields['authorId']->value);
			exit();
		}
	}

	private function deleteAuthor()
	{
		AuthorEntity::remove($this->dbh, $this->keyFields['authorId']->value);
		header("Location: ".$_SERVER['HTTP_REFERER'].AnchorRow::composeRowFragment());
		exit();
	}

	public function executeOperation()
	{
		if(array_key_exists("__operation", $_REQUEST))
		{
			if($this->authorizationManager->authenticated) // Write operations are only allowed when authenticated
			{
				switch($_REQUEST["__operation"])
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
					default:
						$this->viewAuthor();
				}
			}
			else
			{
				header("HTTP/1.1 403 Forbidden");
				throw new Exception("No permissions to modify an author!");
			}
		}
		else
			$this->viewAuthor();
	}
}
?>
