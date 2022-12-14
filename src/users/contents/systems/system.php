<?php
global $route;
\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
?>
<p>
	<a href="?__operation=create_system">Add system</a>
</p>
<?php
global $crudInterface;

\SBData\View\HTML\displayEditableForm($crudInterface->form,
	"Submit",
	"One or more fields are incorrectly specified and marked with a red color!",
	"This field is incorrectly specified!");
?>
