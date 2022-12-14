<?php
global $route;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 2);
?>
<div class="tabpage">
	<?php
	$usersURL = $_SERVER["SCRIPT_NAME"]."/users";
	?>
	<p>
		<a href="<?= $usersURL ?>?__operation=create_user">Add user</a>
	</p>
	<?php
	global $crudInterface;

	\SBData\View\HTML\displayEditableForm($crudInterface->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
	?>
</div>
