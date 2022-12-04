<?php
use SBData\Model\Form;
use SBData\Model\Field\DateField;
use SBData\Model\Field\NumericIntKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\URLField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBExampleApps\Literature\Model\Entity\PaperEntity;

global $dbh, $table;

$composePaperLink = function (NumericIntKeyLinkField $field, Form $form): string
{
	$paperId = $field->exportValue();
	return $_SERVER["PHP_SELF"]."/".rawurlencode($paperId);
};

$deletePaperLink = function (Form $form): string
{
	$paperId = $form->fields["PAPER_ID"]->exportValue();
	return $_SERVER['PHP_SELF']."/".rawurlencode($paperId)."?__operation=delete_paper".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"PAPER_ID" => new NumericIntKeyLinkField("Id", $composePaperLink, true),
	"Title" => new TextField("Title", true, 20, 255),
	"Date" => new DateField("Date", true),
	"URL" => new URLField("URL", false),
	"Comment" => new TextField("Comment", false, 20, 255)
), array(
	"Delete" => $deletePaperLink
));

$table->stmt = PaperEntity::queryAll($dbh, $GLOBALS["query"]["conferenceId"]);
?>
