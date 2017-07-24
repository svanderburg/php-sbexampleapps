<?php
require_once("data/model/Form.class.php");
require_once("data/model/field/TextField.class.php");
require_once("data/model/field/HiddenField.class.php");
require_once("data/model/field/PasswordField.class.php");

global $form, $authorizationManager;

if(array_key_exists("__operation", $_REQUEST) && $_REQUEST["__operation"] == "logout")
{
	$authorizationManager->logout();
	header("Location: ".$_SERVER["HTTP_REFERER"]);
	exit();
}

if($authorizationManager->authenticated)
	$form = null;
else
{
	$form = new Form(array(
		"Referer" => new HiddenField(false),
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

			if($authorizationManager->authenticated)
			{
				header("Location: ".$credentials["Referer"]);
				exit();
			}
			else
				$form = null;
		}
	}
	else
		$form->fields["Referer"]->value = $_SERVER["HTTP_REFERER"];
}
?>
