<?php
require_once("data/model/table/DBTable.class.php");
require_once("data/model/field/KeyLinkField.class.php");
require_once("data/model/field/TextField.class.php");
require_once("data/model/field/URLField.class.php");
require_once("crud/model/CRUDModel.class.php");
require_once("model/entities/ConferenceEntity.class.php");

class ConferencesCRUDModel extends CRUDModel
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
		function composeConferenceLink(KeyLinkField $field, Form $form)
		{
			return $_SERVER["PHP_SELF"]."/".$field->value;
		}

		function deleteConferenceLink(Form $form)
		{
			return $_SERVER["SCRIPT_NAME"]."/conferences/".$form->fields["CONFERENCE_ID"]->value."?__operation=delete_conference";
		}

		$this->table = new DBTable(array(
			"CONFERENCE_ID" => new KeyLinkField("Id", "composeConferenceLink", true),
			"Name" => new TextField("Name", true, 20, 255),
			"Homepage" => new URLField("Homepage", false),
			"PublisherName" => new TextField("Publisher", true, 20, 255),
			"Location" => new TextField("Location", true, 20, 255)
		), array(
			"Delete" => "deleteConferenceLink"
		));

		/* Compose a statement that queries the conferences */
		$this->table->stmt = ConferenceEntity::queryAll($this->dbh);
	}
}
?>
