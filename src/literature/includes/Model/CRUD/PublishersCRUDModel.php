<?php
namespace SBExampleApps\Literature\Model\CRUD;
use PDO;
use SBdata\Model\Form;
use SBData\Model\Field\NumericIntKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Literature\Model\Entity\PublisherEntity;

class PublishersCRUDModel extends CRUDModel
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
		function composePublisherLink(NumericIntKeyLinkField $field, Form $form): string
		{
			$publisherId = $field->exportValue();
			return $_SERVER["PHP_SELF"]."/".$publisherId;
		}

		function deletePublisherLink(Form $form): string
		{
			$publisherId = $form->fields["PUBLISHER_ID"]->exportValue();
			return $_SERVER["SCRIPT_NAME"]."/publishers/".$publisherId."?__operation=delete_publisher".AnchorRow::composeRowParameter($form);
		}

		$this->table = new DBTable(array(
			"PUBLISHER_ID" => new NumericIntKeyLinkField("Id", __NAMESPACE__.'\\composePublisherLink', true),
			"Name" => new TextField("Name", true, 20, 255)
		), array(
			"Delete" => __NAMESPACE__.'\\deletePublisherLink'
		));

		/* Compose a statement that queries the publishers */
		$this->table->stmt = PublisherEntity::queryAll($this->dbh);
	}
}
?>
