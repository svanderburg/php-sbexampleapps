<?php
use SBData\Model\Form;
use SBData\Model\Field\KeyLinkField;
use SBData\Model\Field\TextField;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBExampleApps\Users\Model\Entity\UserEntity;

global $dbh, $table;

$composeUserLink = function (KeyLinkField $field, Form $form): string
{
	$username = $field->exportValue();
	return $_SERVER["PHP_SELF"]."/".rawurlencode($username);
};

$deleteUserLink = function (Form $form): string
{
	$username = $form->fields["Username"]->exportValue();
	return $_SERVER["SCRIPT_NAME"]."/users/".rawurlencode($username)."?__operation=delete_user".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"Username" => new KeyLinkField("Username", $composeUserLink, true),
	"FullName" => new TextField("Full name", true, 20, 255)
), array(
	"Delete" => $deleteUserLink
));

/* Compose a statement that queries the persons */
$table->stmt = UserEntity::queryAll($dbh);
?>
