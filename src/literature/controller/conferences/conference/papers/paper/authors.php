<?php
use SBData\Model\ReadOnlyForm;
use SBData\Model\Label\TextLabel;
use SBData\Model\Table\Action;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBData\Model\Field\NaturalNumberKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\RouteUtils;
use SBExampleApps\Literature\Model\Entity\PaperEntity;

global $dbh, $addAuthorForm, $table;

$addAuthorForm = new CRUDForm(array(
	"AUTHOR_ID" => new DBComboBoxField("Author", $dbh, "SBExampleApps\\Literature\\Model\\Entity\\AuthorEntity::querySummary", "SBExampleApps\\Literature\\Model\\Entity\\AuthorEntity::queryOneSummary", true)
), "__operation", null, new TextLabel("Add author"));

$addAuthorForm->setOperation("insert_paper_author");

$composeAuthorLink = function (NaturalNumberKeyLinkField $field, ReadOnlyForm $form): string
{
	$authorId = $field->exportValue();
	return $_SERVER["SCRIPT_NAME"]."/authors/".rawurlencode($authorId);
};

$deletePaperAuthorLink = function (ReadOnlyForm $form): string
{
	$authorId = $form->fields["AUTHOR_ID"]->exportValue();
	return RouteUtils::composeSelfURL()."/".rawurlencode($authorId)."?__operation=delete_paper_author".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"AUTHOR_ID" => new NaturalNumberKeyLinkField("Id", $composeAuthorLink, true),
	"LastName" => new TextField("Last name", true),
	"FirstName" => new TextField("First name", true)
), array(
	"Delete" => new Action($deletePaperAuthorLink)
), "No authors");

$table->setStatement(PaperEntity::queryAuthors($dbh, $GLOBALS["query"]["paperId"], $GLOBALS["query"]["conferenceId"]));
?>
