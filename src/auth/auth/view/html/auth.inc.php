<?php
require_once("data/view/html/form.inc.php");

global $form, $authorizationManager;

if($form === null)
{
	if($authorizationManager->authenticated)
	{
		?>
		<p>Logged in as: <?php print($_SESSION["Username"]) ?></p>
		<p><a href="<?php print($_SERVER["PHP_SELF"]); ?>?__operation=logout">Logout</a></p>
		<?php
	}
	else
	{
		?>
		<p>Incorrect username and/or password, or no authorization to login to this system!</p>
		<?php
	}
}
else
{
	displayEditableForm($form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}
?>
