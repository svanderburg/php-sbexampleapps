<?php
use SBData\Model\Form;
use SBData\Model\Field\NumericIntKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\URLField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBExampleApps\Literature\Model\Entity\AuthorEntity;

global $dbh, $table;

$composeAuthorLink = function (NumericIntKeyLinkField $field, Form $form): string
{
	$authorId = $field->exportValue();
	return $_SERVER["PHP_SELF"]."/".$authorId;
};

$deleteAuthorLink = function (Form $form): string
{
	$authorId = $form->fields["AUTHOR_ID"]->exportValue();
	return $_SERVER["SCRIPT_NAME"]."/authors/".$authorId."?__operation=delete_author".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"AUTHOR_ID" => new NumericIntKeyLinkField("Id", $composeAuthorLink, true),
	"FirstName" => new TextField("First name", true, 20, 255),
	"LastName" => new TextField("Last name", true, 20, 255),
	"Homepage" => new URLField("Homepage", false),
), array(
	"Delete" => $deleteAuthorLink
));

/* Compose a statement that queries the authors */
$table->stmt = AuthorEntity::queryAll($dbh);
?>
