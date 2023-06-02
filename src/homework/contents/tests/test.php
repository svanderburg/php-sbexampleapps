<?php
\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 2);
?>
<div class="tabpage">
	<?php
	$testsURL = $_SERVER["SCRIPT_NAME"]."/tests";

	global $route, $crudInterface;
	\SBCrud\View\HTML\displayOperationToolbar($route);

	\SBData\View\HTML\displayEditableForm($crudInterface->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
	?>
</div>
