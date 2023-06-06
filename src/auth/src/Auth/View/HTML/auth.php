<?php
use SBCrud\Model\RouteUtils;

global $form, $authorizationManager;

if($form === null)
{
	if($authorizationManager->authenticated)
	{
		?>
		<p>Logged in as: <?= $_SESSION["Username"] ?></p>
		<p><a href="<?= RouteUtils::composeSelfURL() ?>?__operation=logout">Logout</a></p>
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
	\SBData\View\HTML\displayEditableForm($form);
}
?>
