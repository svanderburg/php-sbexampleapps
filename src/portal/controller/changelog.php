<?php
use SBData\Model\Form;
use SBData\Model\Field\DateField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\Action;
use SBData\Model\Table\EditableDBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBExampleApps\Portal\Model\Entity\ChangeLogEntriesEntity;

global $dbh, $table, $submittedForm;

$selfURL = RouteUtils::composeSelfURL();

$deleteChangeLogLink = function (Form $form) use ($selfURL): string
{
	$logId = $form->fields["LOG_ID"]->exportValue();
	return $selfURL."/".rawurlencode($logId)."?__operation=remove_changelogentry".AnchorRow::composeRowParameter($form);
};

$table = new EditableDBTable(array(
	"LOG_ID" => new TextField("Version", true, 10, 255),
	"Date" => new DateField("Date", true, true),
	"Summary" => new TextField("Summary", true, 30, 255),
	"old_LOG_ID" => new HiddenField(true),
), array(
	"Delete" => new Action($deleteChangeLogLink)
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

$table->setStatement(ChangeLogEntriesEntity::queryAll($dbh));
?>
