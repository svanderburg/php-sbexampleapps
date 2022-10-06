<?php
namespace SBExampleApps\Users\Model\CRUD;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\KeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Users\Model\Entity\SystemEntity;

class SystemsCRUDModel extends CRUDModel
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
		function composeSystemLink(KeyLinkField $field, Form $form): string
		{
			$systemId = $field->exportValue();
			return $_SERVER["PHP_SELF"]."/".$systemId;
		}

		function deleteSystemLink(Form $form): string
		{
			$systemId = $form->fields["SYSTEM_ID"]->exportValue();
			return $_SERVER["SCRIPT_NAME"]."/systems/".$systemId."?__operation=delete_system".AnchorRow::composeRowParameter($form);
		}

		$this->table = new DBTable(array(
			"SYSTEM_ID" => new KeyLinkField("Id", __NAMESPACE__.'\\composeSystemLink', true),
			"Description" => new TextField("Description", true, 20, 255),
		), array(
			"Delete" => __NAMESPACE__.'\\deleteSystemLink'
		));

		/* Compose a statement that queries the persons */
		$this->table->stmt = SystemEntity::queryAll($this->dbh);
	}
}
?>
