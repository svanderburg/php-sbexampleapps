<?php
require_once("data/model/table/DBTable.class.php");
require_once("data/model/field/KeyLinkField.class.php");
require_once("data/model/field/TextField.class.php");
require_once("crud/model/CRUDModel.class.php");
require_once("model/entities/TestEntity.class.php");

class TestsCRUDModel extends CRUDModel
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
		function composeTestLink(KeyLinkField $field, Form $form)
		{
			return $_SERVER["PHP_SELF"]."/".$field->value;
		}

		function deleteTestLink(Form $form)
		{
			return $_SERVER["SCRIPT_NAME"]."/tests/".$form->fields["TEST_ID"]->value."?__operation=delete_test";
		}

		$this->table = new DBTable(array(
			"TEST_ID" => new KeyLinkField("Id", "composeTestLink", true),
			"Title" => new TextField("Title", true, 20, 255),
		), array(
			"Delete" => "deleteTestLink"
		));

		/* Compose a statement that queries the persons */
		$this->table->stmt = TestEntity::queryAll($this->dbh);
	}
}
?>
