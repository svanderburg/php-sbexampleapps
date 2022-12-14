<?php
use SBData\Model\Form;
use SBData\Model\Field\NumericIntKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\URLField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBExampleApps\Literature\Model\Entity\ConferenceEntity;

global $dbh, $table;

$selfURL = RouteUtils::composeSelfURL();

$composeConferenceLink = function (NumericIntKeyLinkField $field, Form $form) use ($selfURL): string
{
	$conferenceId = $field->exportValue();
	return $selfURL."/".rawurlencode($conferenceId);
};

$deleteConferenceLink = function (Form $form): string
{
	$conferenceId = $form->fields["CONFERENCE_ID"]->exportValue();
	return $_SERVER["SCRIPT_NAME"]."/conferences/".rawurlencode($conferenceId)."?__operation=delete_conference".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"CONFERENCE_ID" => new NumericIntKeyLinkField("Id", $composeConferenceLink, true),
	"Name" => new TextField("Name", true, 20, 255),
	"Homepage" => new URLField("Homepage", false),
	"PublisherName" => new TextField("Publisher", true, 20, 255),
	"Location" => new TextField("Location", true, 20, 255)
), array(
	"Delete" => $deleteConferenceLink
));

/* Compose a statement that queries the conferences */
$table->stmt = ConferenceEntity::queryAll($dbh);
?>
