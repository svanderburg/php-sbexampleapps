<?php
global $route, $crudInterface, $authorizationManager;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);

$publishersURL = $_SERVER["SCRIPT_NAME"]."/publishers";

if($authorizationManager->authenticated)
{
	?>
	<p>
		<a href="<?= $publishersURL ?>?__operation=create_publisher">Add publisher</a>
	</p>
	<?php
}

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