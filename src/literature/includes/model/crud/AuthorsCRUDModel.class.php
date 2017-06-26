<?php
require_once("data/model/table/DBTable.class.php");
require_once("data/model/field/KeyLinkField.class.php");
require_once("data/model/field/TextField.class.php");
require_once("data/model/field/URLField.class.php");
require_once("crud/model/CRUDModel.class.php");
require_once("model/entities/AuthorEntity.class.php");

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

		$this->table = new DBTable(array(
			"AUTHOR_ID" => new KeyLinkField("Id", "composeAuthorLink", true),
			"FirstName" => new TextField("First name", true, 20, 255),
			"LastName" => new TextField("Last name", true, 20, 255),
			"Homepage" => new URLField("Homepage", false),
		));

		/* Compose a statement that queries the authors */
		$this->table->stmt = AuthorEntity::queryAll($this->dbh);
	}
}
?>
