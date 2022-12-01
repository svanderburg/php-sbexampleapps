<?php
use SBData\Model\Form;
use SBData\Model\Field\NumericIntKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBExampleApps\Homework\Model\Entity\TestEntity;

global $dbh, $table;

$composeTestLink = function (NumericIntKeyLinkField $field, Form $form): string
{
	$testId = $field->exportValue();
	return $_SERVER["PHP_SELF"]."/".rawurlencode($testId);
};

$deleteTestLink = function (Form $form): string
{
	$testId = $form->fields["TEST_ID"]->exportValue();
	return $_SERVER["PHP_SELF"]."/".rawurlencode($testId)."?__operation=delete_test".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"TEST_ID" => new NumericIntKeyLinkField("Id", $composeTestLink, true),
	"Title" => new TextField("Title", true, 20, 255),
), array(
	"Delete" => $deleteTestLink
));

/* Compose a statement that queries the persons */
$table->stmt = TestEntity::queryAll($dbh);
?>
