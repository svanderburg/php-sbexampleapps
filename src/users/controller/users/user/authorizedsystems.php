<?php
use SBData\Model\Form;
use SBData\Model\ReadOnlyForm;
use SBData\Model\Label\TextLabel;
use SBData\Model\Table\Action;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBData\Model\Field\KeyLinkField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\RouteUtils;
use SBExampleApps\Users\Model\Entity\UserEntity;

global $dbh, $addSystemForm, $table;

$addSystemForm = new CRUDForm(array(
	"SYSTEM_ID" => new DBComboBoxField("System", $dbh, "SBExampleApps\\Users\\Model\\Entity\\SystemEntity::queryAll", "SBExampleApps\\Users\\Model\\Entity\\SystemEntity::queryOne", true)
), "__operation", null, new TextLabel("Add system"));

$addSystemForm->setOperation("insert_user_system");

$composeSystemLink = function (KeyLinkField $field, ReadOnlyForm $form): string
{
	$systemId = $field->exportValue();
	return $_SERVER["SCRIPT_NAME"]."/systems/".rawurlencode($systemId);
};

$deleteUserSystemLink = function (ReadOnlyForm $form): string
{
	$systemId = $form->fields["SYSTEM_ID"]->exportValue();
	return RouteUtils::composeSelfURL()."/".rawurlencode($systemId)."?__operation=delete_user_system".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"SYSTEM_ID" => new KeyLinkField("Id", $composeSystemLink, true),
	"Description" => new TextField("Description", true)
), array(
	"Delete" => new Action($deleteUserSystemLink)
));

$table->setStatement(UserEntity::queryAllAuthorizedSystems($dbh, $GLOBALS["query"]["Username"]));
?>
