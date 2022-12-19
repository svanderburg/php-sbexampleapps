<?php
use SBData\Model\Form;
use SBData\Model\Field\DateField;
use SBData\Model\Field\NaturalNumberKeyLinkField;
use SBData\Model\Field\MetaDataField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\URLField;
use SBData\Model\Table\DBTable;
use SBExampleApps\Literature\Model\Entity\PaperEntity;

global $dbh, $form, $table;

$form = new Form(array(
	"keyword" => new TextField("Keyword", true, 20, 255)
));

if(array_key_exists("keyword", $_REQUEST))
{
	$form->importValues($_REQUEST);
	$form->checkFields();
	if(!$form->checkValid())
		return;

	$composePaperLink = function (NaturalNumberKeyLinkField $field, Form $form): string
	{
		$paperId = $field->exportValue();
		$conferenceId = $form->fields["CONFERENCE_ID"]->exportValue();
		return $_SERVER["SCRIPT_NAME"]."/conferences/".rawurlencode($conferenceId)."/papers/".rawurlencode($paperId);
	};

	$composeConferenceLink = function (NaturalNumberKeyLinkField $field, Form $form): string
	{
		$conferenceId = $form->fields["CONFERENCE_ID"]->exportValue();
		return $_SERVER["SCRIPT_NAME"]."/conferences/".rawurlencode($conferenceId);
	};

	$composePublisherLink = function (NaturalNumberKeyLinkField $field, Form $form): string
	{
		$publisherId = $form->fields["PUBLISHER_ID"]->exportValue();
		return $_SERVER["SCRIPT_NAME"]."/publishers/".rawurlencode($publisherId);
	};

	/* Construct a table containing the resulting papers */
	$table = new DBTable(array(
		"PAPER_ID" => new NaturalNumberKeyLinkField("Id", $composePaperLink, true),
		"Title" => new TextField("Title", true, 20, 255),
		"Date" => new DateField("Date", true),
		"URL" => new URLField("URL", false),
		"PUBLISHER_ID" => new MetaDataField(true, 255),
		"PublisherName" => new NaturalNumberKeyLinkField("Publisher", $composePublisherLink, true),
		"CONFERENCE_ID" => new MetaDataField(true, 255),
		"ConferenceName" => new NaturalNumberKeyLinkField("Conference", $composeConferenceLink, true)
	));

	$table->stmt = PaperEntity::searchByKeyword($dbh, $form->fields["keyword"]->exportValue());
}
?>
