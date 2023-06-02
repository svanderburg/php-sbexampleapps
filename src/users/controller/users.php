<?php
use SBData\Model\ReadOnlyForm;
use SBData\Model\Field\KeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\Action;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBExampleApps\Users\Model\Entity\UserEntity;

global $dbh, $table;

$selfURL = RouteUtils::composeSelfURL();

$composeUserLink = function (KeyLinkField $field, ReadOnlyForm $form) use ($selfURL): string
{
	$username = $field->exportValue();
	return $selfURL."/".rawurlencode($username);
};

$deleteUserLink = function (ReadOnlyForm $form): string
{
	$username = $form->fields["Username"]->exportValue();
	return $_SERVER["SCRIPT_NAME"]."/users/".rawurlencode($username)."?__operation=delete_user".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"Username" => new KeyLinkField("Username", $composeUserLink, true),
	"FullName" => new TextField("Full name", true, 20, 255)
), array(
	"Delete" => new Action($deleteUserLink)
));

/* Compose a statement that queries the persons */
$table->setStatement(UserEntity::queryAll($dbh));
?>
