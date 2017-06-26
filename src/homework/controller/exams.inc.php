<?php
require_once("data/model/Form.class.php");
require_once("data/model/field/comboboxfield/DBComboBoxField.class.php");
require_once("model/entities/TestEntity.class.php");

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
