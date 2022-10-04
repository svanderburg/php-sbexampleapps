<?php
namespace SBExampleApps\Literature\Model\CRUD;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\NumericIntKeyLinkField;
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
	public PDO $dbh;

	public ?DBTable $table = null;

	public function __construct(CRUDPage $crudPage, PDO $dbh)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
	}

	public function executeOperation(): void
	{
		function composeConferenceLink(NumericIntKeyLinkField $field, Form $form): string
		{
			$conferenceId = $field->exportValue();
			return $_SERVER["PHP_SELF"]."/".$conferenceId;
		}

		function deleteConferenceLink(Form $form): string
		{
			$conferenceId = $form->fields["CONFERENCE_ID"]->exportValue();
			return $_SERVER["SCRIPT_NAME"]."/conferences/".$conferenceId."?__operation=delete_conference".AnchorRow::composeRowParameter($form);
		}

		$this->table = new DBTable(array(
			"CONFERENCE_ID" => new NumericIntKeyLinkField("Id", __NAMESPACE__.'\\composeConferenceLink', true),
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
