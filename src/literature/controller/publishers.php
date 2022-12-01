<?php
use SBdata\Model\Form;
use SBData\Model\Field\NumericIntKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBExampleApps\Literature\Model\Entity\PublisherEntity;

global $dbh, $table;

$composePublisherLink = function (NumericIntKeyLinkField $field, Form $form): string
{
	$publisherId = $field->exportValue();
	return $_SERVER["PHP_SELF"]."/".rawurlencode($publisherId);
};

$deletePublisherLink = function (Form $form): string
{
	$publisherId = $form->fields["PUBLISHER_ID"]->exportValue();
	return $_SERVER["SCRIPT_NAME"]."/publishers/".rawurlencode($publisherId)."?__operation=delete_publisher".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"PUBLISHER_ID" => new NumericIntKeyLinkField("Id", $composePublisherLink, true),
	"Name" => new TextField("Name", true, 20, 255)
), array(
	"Delete" => $deletePublisherLink
));

/* Compose a statement that queries the publishers */
$table->stmt = PublisherEntity::queryAll($dbh);
?>
