<?php
namespace SBExampleApps\Literature\Model\CRUD;
use PDO;
use SBdata\Model\Form;
use SBData\Model\Field\KeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\DBTable;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Literature\Model\Entity\PublisherEntity;

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
			"PUBLISHER_ID" => new KeyLinkField("Id", __NAMESPACE__.'\\composePublisherLink', true),
			"Name" => new TextField("Name", true, 20, 255)
		), array(
			"Delete" => __NAMESPACE__.'\\deletePublisherLink'
		));

		/* Compose a statement that queries the publishers */
		$this->table->stmt = PublisherEntity::queryAll($this->dbh);
	}
}
?>
