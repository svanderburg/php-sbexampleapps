<?php
require_once("data/model/Form.class.php");
require_once("data/model/field/TextField.class.php");
require_once("data/model/field/PasswordField.class.php");

global $form, $authorizationManager;

if(array_key_exists("__operation", $_REQUEST) && $_REQUEST["__operation"] == "logout")
{
	$authorizationManager->logout();
}

if($authorizationManager->authenticated)
	$form = null;
else
{
	$form = new Form(array(
		"Username" => new TextField("Username", true),
		"Password" => new PasswordField("Password", true)
	));

	if($_SERVER["REQUEST_METHOD"] === "POST")
	{
		$form->importValues($_POST);
		$form->checkFields();

		if($form->checkValid())
		{
			$credentials = $form->exportValues();
			$authorizationManager->login($credentials);
			$form = null;
		}
	}
}
?>
