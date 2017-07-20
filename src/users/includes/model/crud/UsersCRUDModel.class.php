<?php
require_once("data/model/table/DBTable.class.php");
require_once("data/model/field/KeyLinkField.class.php");
require_once("data/model/field/TextField.class.php");
require_once("crud/model/CRUDModel.class.php");
require_once("model/entities/UserEntity.class.php");

class UsersCRUDModel extends CRUDModel
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
		function composeUserLink(KeyLinkField $field, Form $form)
		{
			return $_SERVER["PHP_SELF"]."/".$field->value;
		}

		function deleteUserLink(Form $form)
		{
			return $_SERVER["SCRIPT_NAME"]."/users/".$form->fields["Username"]->value."?__operation=delete_user";
		}

		$this->table = new DBTable(array(
			"Username" => new KeyLinkField("Username", "composeUserLink", true),
			"FullName" => new TextField("Full name", true, 20, 255)
		), array(
			"Delete" => "deleteUserLink"
		));

		/* Compose a statement that queries the persons */
		$this->table->stmt = UserEntity::queryAll($this->dbh);
	}
}
?>
