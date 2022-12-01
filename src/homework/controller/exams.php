<?php
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBData\Model\Form;

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
		header("Location: ".$_SERVER["PHP_SELF"]."/".$form->fields["testId"]->exportValue());
		exit;
	}
}
?>
