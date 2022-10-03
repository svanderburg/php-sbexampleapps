<?php
namespace SBExampleApps\Homework\Model\CRUD;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\NumericIntKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Homework\Model\Entity\TestEntity;

class TestsCRUDModel extends CRUDModel
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
		function composeTestLink(NumericIntKeyLinkField $field, Form $form): string
		{
			$testId = $field->exportValue();
			return $_SERVER["PHP_SELF"]."/".$testId;
		}

		function deleteTestLink(Form $form): string
		{
			$testId = $form->fields["TEST_ID"]->exportValue();
			return $_SERVER["SCRIPT_NAME"]."/tests/".$testId."?__operation=delete_test".AnchorRow::composePreviousRowParameter($form);
		}

		$this->table = new DBTable(array(
			"TEST_ID" => new NumericIntKeyLinkField("Id", __NAMESPACE__.'\\composeTestLink', true),
			"Title" => new TextField("Title", true, 20, 255),
		), array(
			"Delete" => __NAMESPACE__.'\\deleteTestLink'
		));

		/* Compose a statement that queries the persons */
		$this->table->stmt = TestEntity::queryAll($this->dbh);
	}
}
?>
