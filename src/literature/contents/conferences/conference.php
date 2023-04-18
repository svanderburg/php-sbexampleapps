<?php
global $route, $crudInterface, $authorizationManager;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 2);
?>
<div class="tabpage">
	<?php
	$conferencesURL = $_SERVER["SCRIPT_NAME"]."/conferences";

	if($authorizationManager->authenticated)
	{
		?>
		<p>
			<a href="<?= $conferencesURL ?>?__operation=create_conference">Add conference</a>
		</p>
		<?php
	}

	/* Display conference properties */
	if($authorizationManager->authenticated)
	{
		\SBData\View\HTML\displayEditableForm($crudInterface->form,
			"Submit",
			"One or more fields are incorrectly specified and marked with a red color!",
			"This field is incorrectly specified!");
	}
	else
		\SBData\View\HTML\displayForm($crudInterface->form);
	?>
</div>