<?php
use SBData\Model\Form;
use SBData\Model\Field\NaturalNumberKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBExampleApps\Homework\Model\Entity\TestEntity;

global $dbh, $table;

$selfURL = RouteUtils::composeSelfURL();

$composeTestLink = function (NaturalNumberKeyLinkField $field, Form $form) use ($selfURL): string
{
	$testId = $field->exportValue();
	return $selfURL."/".rawurlencode($testId);
};

$deleteTestLink = function (Form $form) use ($selfURL): string
{
	$testId = $form->fields["TEST_ID"]->exportValue();
	return $selfURL."/".rawurlencode($testId)."?__operation=delete_test".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"TEST_ID" => new NaturalNumberKeyLinkField("Id", $composeTestLink, true),
	"Title" => new TextField("Title", true, 20, 255),
), array(
	"Delete" => $deleteTestLink
));

/* Compose a statement that queries the persons */
$table->stmt = TestEntity::queryAll($dbh);
?>
