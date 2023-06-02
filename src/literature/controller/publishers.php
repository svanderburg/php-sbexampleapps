<?php
use SBdata\Model\ReadOnlyForm;
use SBData\Model\Field\NaturalNumberKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\Action;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBExampleApps\Literature\Model\Entity\PublisherEntity;

global $dbh, $table;

$selfURL = RouteUtils::composeSelfURL();

$composePublisherLink = function (NaturalNumberKeyLinkField $field, ReadOnlyForm $form) use ($selfURL): string
{
	$publisherId = $field->exportValue();
	return $selfURL."/".rawurlencode($publisherId);
};

$deletePublisherLink = function (ReadOnlyForm $form): string
{
	$publisherId = $form->fields["PUBLISHER_ID"]->exportValue();
	return $_SERVER["SCRIPT_NAME"]."/publishers/".rawurlencode($publisherId)."?__operation=delete_publisher".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"PUBLISHER_ID" => new NaturalNumberKeyLinkField("Id", $composePublisherLink, true),
	"Name" => new TextField("Name", true, 20, 255)
), array(
	"Delete" => new Action($deletePublisherLink)
));

/* Compose a statement that queries the publishers */
$table->setStatement(PublisherEntity::queryAll($dbh));
?>
