<?php
require_once("data/model/table/DBTable.class.php");
require_once("data/model/field/KeyLinkField.class.php");
require_once("data/model/field/TextField.class.php");
require_once("crud/model/CRUDModel.class.php");
require_once("model/entities/PublisherEntity.class.php");

class PublishersCRUDModel extends CRUDModel
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
		function composePublisherLink(KeyLinkField $field, Form $form)
		{
			return $_SERVER["PHP_SELF"]."/".$field->value;
		}

		function deletePublisherLink(Form $form)
		{
			return $_SERVER["SCRIPT_NAME"]."/publishers/".$form->fields["PUBLISHER_ID"]->value."?__operation=delete_publisher";
		}

		$this->table = new DBTable(array(
			"PUBLISHER_ID" => new KeyLinkField("Id", "composePublisherLink", true),
			"Name" => new TextField("Name", true, 20, 255)
		), array(
			"Delete" => "deletePublisherLink"
		));

		/* Compose a statement that queries the publishers */
		$this->table->stmt = PublisherEntity::queryAll($this->dbh);
	}
}
?>
