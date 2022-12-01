<?php
use SBData\Model\Form;
use SBData\Model\Field\DateField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBExampleApps\Portal\Model\Entity\ChangeLogEntriesEntity;

global $dbh, $table, $submittedForm;

$deleteChangeLogLink = function (Form $form): string
{
	$logId = $form->fields["LOG_ID"]->exportValue();
	return $_SERVER["PHP_SELF"]."/".rawurlencode($logId)."?__operation=remove_changelogentry".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"LOG_ID" => new TextField("Version", true, 10, 255),
	"Date" => new DateField("Date", true, true),
	"Summary" => new TextField("Summary", true, 30, 255),
	"old_LOG_ID" => new HiddenField(true),
), array(
	"Delete" => $deleteChangeLogLink
));

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$submittedForm = $table->constructForm();
	$submittedForm->importValues($_POST);
	$submittedForm->checkFields();

	/* Update the record if it is valid */
	if($submittedForm->checkValid())
	{
		$entry = $submittedForm->exportValues();
		ChangeLogEntriesEntity::update($dbh, $entry, $entry["old_LOG_ID"]);
	}
}

$table->stmt = ChangeLogEntriesEntity::queryAll($dbh);
?>
