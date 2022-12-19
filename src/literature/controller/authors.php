<?php
use SBData\Model\Form;
use SBData\Model\Field\NaturalNumberKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\URLField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBExampleApps\Literature\Model\Entity\AuthorEntity;

global $dbh, $table;

$selfURL = RouteUtils::composeSelfURL();

$composeAuthorLink = function (NaturalNumberKeyLinkField $field, Form $form) use ($selfURL): string
{
	$authorId = $field->exportValue();
	return $selfURL."/".rawurlencode($authorId);
};

$deleteAuthorLink = function (Form $form): string
{
	$authorId = $form->fields["AUTHOR_ID"]->exportValue();
	return $_SERVER["SCRIPT_NAME"]."/authors/".rawurlencode($authorId)."?__operation=delete_author".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"AUTHOR_ID" => new NaturalNumberKeyLinkField("Id", $composeAuthorLink, true),
	"FirstName" => new TextField("First name", true, 20, 255),
	"LastName" => new TextField("Last name", true, 20, 255),
	"Homepage" => new URLField("Homepage", false),
), array(
	"Delete" => $deleteAuthorLink
));

/* Compose a statement that queries the authors */
$table->stmt = AuthorEntity::queryAll($dbh);
?>
