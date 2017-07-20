<?php
require_once("data/model/table/DBTable.class.php");
require_once("data/model/field/KeyLinkField.class.php");
require_once("data/model/field/TextField.class.php");
require_once("crud/model/CRUDModel.class.php");
require_once("model/entities/SystemEntity.class.php");

class SystemsCRUDModel extends CRUDModel
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
		function composeSystemLink(KeyLinkField $field, Form $form)
		{
			return $_SERVER["PHP_SELF"]."/".$field->value;
		}

		function deleteSystemLink(Form $form)
		{
			return $_SERVER["SCRIPT_NAME"]."/systems/".$form->fields["SYSTEM_ID"]->value."?__operation=delete_system";
		}

		$this->table = new DBTable(array(
			"SYSTEM_ID" => new KeyLinkField("Id", "composeSystemLink", true),
			"Description" => new TextField("Description", true, 20, 255),
		), array(
			"Delete" => "deleteSystemLink"
		));

		/* Compose a statement that queries the persons */
		$this->table->stmt = SystemEntity::queryAll($this->dbh);
	}
}
?>
