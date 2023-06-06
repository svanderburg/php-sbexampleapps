<?php
use SBData\Model\ReadOnlyForm;
use SBData\Model\Label\TextLabel;
use SBData\Model\Table\Action;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBData\Model\Field\NaturalNumberKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBCrud\Model\RouteUtils;
use SBCrud\Model\CRUDForm;
use SBExampleApps\Literature\Model\Entity\ConferenceEntity;

global $dbh, $addEditorForm, $table;

$addEditorForm = new CRUDForm(array(
	"AUTHOR_ID" => new DBComboBoxField("Editor", $dbh, "SBExampleApps\\Literature\\Model\\Entity\\AuthorEntity::querySummary", "SBExampleApps\\Literature\\Model\\Entity\\AuthorEntity::queryOneSummary", true)
), "__operation", null, new TextLabel("Add editor"));
$addEditorForm->setOperation("insert_conference_author");

$composeAuthorLink = function (NaturalNumberKeyLinkField $field, ReadOnlyForm $form): string
{
	$authorId = $field->exportValue();
	return $_SERVER["SCRIPT_NAME"]."/authors/".rawurlencode($authorId);
};

$deleteConferenceAuthorLink = function (ReadOnlyForm $form): string
{
	$authorId = $form->fields["AUTHOR_ID"]->exportValue();
	return RouteUtils::composeSelfURL()."/".rawurlencode($authorId)."?__operation=delete_conference_author".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"AUTHOR_ID" => new NaturalNumberKeyLinkField("Id", $composeAuthorLink, true),
	"LastName" => new TextField("Last name", true, 20, 255),
	"FirstName" => new TextField("First name", true, 20, 255)
), array(
	"Delete" => new Action($deleteConferenceAuthorLink)
), "No editors");

$table->setStatement(ConferenceEntity::queryEditors($dbh, $GLOBALS["query"]["conferenceId"]));
?>
