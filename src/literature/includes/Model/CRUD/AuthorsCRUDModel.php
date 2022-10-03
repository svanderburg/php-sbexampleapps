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
use SBExampleApps\Literature\Model\Entity\AuthorEntity;

class AuthorsCRUDModel extends CRUDModel
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
		function composeAuthorLink(NumericIntKeyLinkField $field, Form $form): string
		{
			$authorId = $field->exportValue();
			return $_SERVER["PHP_SELF"]."/".$authorId;
		}

		function deleteAuthorLink(Form $form): string
		{
			$authorId = $form->fields["AUTHOR_ID"]->exportValue();
			return $_SERVER["SCRIPT_NAME"]."/authors/".$authorId."?__operation=delete_author".AnchorRow::composePreviousRowParameter($form);
		}

		$this->table = new DBTable(array(
			"AUTHOR_ID" => new NumericIntKeyLinkField("Id", __NAMESPACE__.'\\composeAuthorLink', true),
			"FirstName" => new TextField("First name", true, 20, 255),
			"LastName" => new TextField("Last name", true, 20, 255),
			"Homepage" => new URLField("Homepage", false),
		), array(
			"Delete" => __NAMESPACE__.'\\deleteAuthorLink'
		));

		/* Compose a statement that queries the authors */
		$this->table->stmt = AuthorEntity::queryAll($this->dbh);
	}
}
?>
