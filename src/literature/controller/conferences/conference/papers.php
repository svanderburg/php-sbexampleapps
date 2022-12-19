<?php
use SBData\Model\Form;
use SBData\Model\Field\DateField;
use SBData\Model\Field\NaturalNumberKeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\URLField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBExampleApps\Literature\Model\Entity\PaperEntity;

global $dbh, $table;

$selfURL = RouteUtils::composeSelfURL();

$composePaperLink = function (NaturalNumberKeyLinkField $field, Form $form) use ($selfURL): string
{
	$paperId = $field->exportValue();
	return $selfURL."/".rawurlencode($paperId);
};

$deletePaperLink = function (Form $form) use ($selfURL): string
{
	$paperId = $form->fields["PAPER_ID"]->exportValue();
	return $selfURL."/".rawurlencode($paperId)."?__operation=delete_paper".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"PAPER_ID" => new NaturalNumberKeyLinkField("Id", $composePaperLink, true),
	"Title" => new TextField("Title", true, 20, 255),
	"Date" => new DateField("Date", true),
	"URL" => new URLField("URL", false),
	"Comment" => new TextField("Comment", false, 20, 255)
), array(
	"Delete" => $deletePaperLink
));

$table->stmt = PaperEntity::queryAll($dbh, $GLOBALS["query"]["conferenceId"]);
?>
