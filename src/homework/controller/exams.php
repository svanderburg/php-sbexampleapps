<?php
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBData\Model\Form;
use SBCrud\Model\RouteUtils;

global $form, $dbh;

$form = new Form(array(
	"testId" => new DBComboBoxField("Test", $dbh, "SBExampleApps\\Homework\\Model\\Entity\\TestEntity::queryAll", "SBExampleApps\\Homework\\Model\\Entity\\TestEntity::queryOne", true)
));

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$form->importValues($_POST);
	$form->checkFields();
	
	if($form->checkValid())
	{
		header("Location: ".RouteUtils::composeSelfURL()."/".$form->fields["testId"]->exportValue());
		exit;
	}
}
?>
