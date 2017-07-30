<?php
use SBData\Model\Form;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\PasswordField;
use SBData\Model\Field\TextField;

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
