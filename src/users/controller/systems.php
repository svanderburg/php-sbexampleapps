<?php
use SBData\Model\Form;
use SBData\Model\Field\KeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBExampleApps\Users\Model\Entity\SystemEntity;

global $dbh, $table;

$selfURL = RouteUtils::composeSelfURL();

$composeSystemLink = function (KeyLinkField $field, Form $form) use ($selfURL): string
{
	$systemId = $field->exportValue();
	return $selfURL."/".rawurlencode($systemId);
};

$deleteSystemLink = function (Form $form): string
{
	$systemId = $form->fields["SYSTEM_ID"]->exportValue();
	return $_SERVER["SCRIPT_NAME"]."/systems/".rawurlencode($systemId)."?__operation=delete_system".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"SYSTEM_ID" => new KeyLinkField("Id", $composeSystemLink, true),
	"Description" => new TextField("Description", true, 20, 255),
), array(
	"Delete" => $deleteSystemLink
));

/* Compose a statement that queries the persons */
$table->stmt = SystemEntity::queryAll($dbh);
?>
