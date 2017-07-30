<?php
namespace SBExampleApps\Homework\Model\CRUD;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\KeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\DBTable;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Homework\Model\Entity\TestEntity;

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
			"TEST_ID" => new KeyLinkField("Id", __NAMESPACE__.'\\composeTestLink', true),
			"Title" => new TextField("Title", true, 20, 255),
		), array(
			"Delete" => __NAMESPACE__.'\\deleteTestLink'
		));

		/* Compose a statement that queries the persons */
		$this->table->stmt = TestEntity::queryAll($this->dbh);
	}
}
?>
