<?php
namespace SBExampleApps\Literature\Model\CRUD;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\KeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\URLField;
use SBData\Model\Table\DBTable;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Literature\Model\Entity\AuthorEntity;

class AuthorsCRUDModel extends CRUDModel
{
	public $dbh;

	public $table = null;

	public function __construct(CRUDPage $crudPage, PDO $dbh)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
	}

	public function executeOperation()
	{
		function composeAuthorLink(KeyLinkField $field, Form $form)
		{
			return $_SERVER["PHP_SELF"]."/".$field->value;
		}

		function deleteAuthorLink(Form $form)
		{
			return $_SERVER["SCRIPT_NAME"]."/authors/".$form->fields["AUTHOR_ID"]->value."?__operation=delete_author";
		}

		$this->table = new DBTable(array(
			"AUTHOR_ID" => new KeyLinkField("Id", __NAMESPACE__.'\\composeAuthorLink', true),
			"FirstName" => new TextField("First name", true, 20, 255),
			"LastName" => new TextField("Last name", true, 20, 255),
			"Homepage" => new URLField("Homepage", false),
		), array(
			"Delete" => __NAMESPACE__.'\\deleteAuthorLink'
		));

		/* Compose a statement that queries the authors */
		$this->table->stmt = AuthorEntity::queryAll($this->dbh);
	}
}
?>
