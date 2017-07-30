<?php
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBData\Model\Form;
use SBExampleApps\Homework\Model\Entity\TestEntity;

global $form, $dbh;

$stmt = TestEntity::queryAll($dbh);

$form = new Form(array(
	"testId" => new DBComboBoxField("Test", $stmt, true)
));

if(count($_POST) > 0)
{
	$form->importValues($_POST);
	$form->checkFields();
	
	if($form->checkValid())
	{
		header("Location: ".$_SERVER["PHP_SELF"]."/".$form->fields["testId"]->value);
		exit;
	}
}
?>
