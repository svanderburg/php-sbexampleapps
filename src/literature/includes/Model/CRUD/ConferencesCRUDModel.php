<?php
namespace SBExampleApps\Literature\Model\CRUD;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\KeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\URLField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\Entity\ConferenceEntity;

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
			return $_SERVER["SCRIPT_NAME"]."/conferences/".$form->fields["CONFERENCE_ID"]->value."?__operation=delete_conference".AnchorRow::composePreviousRowParameter($form);
		}

		$this->table = new DBTable(array(
			"CONFERENCE_ID" => new KeyLinkField("Id", __NAMESPACE__.'\\composeConferenceLink', true),
			"Name" => new TextField("Name", true, 20, 255),
			"Homepage" => new URLField("Homepage", false),
			"PublisherName" => new TextField("Publisher", true, 20, 255),
			"Location" => new TextField("Location", true, 20, 255)
		), array(
			"Delete" => __NAMESPACE__.'\\deleteConferenceLink'
		));

		/* Compose a statement that queries the conferences */
		$this->table->stmt = ConferenceEntity::queryAll($this->dbh);
	}
}
?>
