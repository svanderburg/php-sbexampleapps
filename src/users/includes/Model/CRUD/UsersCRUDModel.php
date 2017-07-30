<?php
namespace SBExampleApps\Users\Model\CRUD;
use PDO;
use SBData\Model\Form;
use SBData\Model\Field\KeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\DBTable;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Users\Model\Entity\UserEntity;

class UsersCRUDModel extends CRUDModel
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
		function composeUserLink(KeyLinkField $field, Form $form)
		{
			return $_SERVER["PHP_SELF"]."/".$field->value;
		}

		function deleteUserLink(Form $form)
		{
			return $_SERVER["SCRIPT_NAME"]."/users/".$form->fields["Username"]->value."?__operation=delete_user";
		}

		$this->table = new DBTable(array(
			"Username" => new KeyLinkField("Username", __NAMESPACE__.'\\composeUserLink', true),
			"FullName" => new TextField("Full name", true, 20, 255)
		), array(
			"Delete" => __NAMESPACE__.'\\deleteUserLink'
		));

		/* Compose a statement that queries the persons */
		$this->table->stmt = UserEntity::queryAll($this->dbh);
	}
}
?>
