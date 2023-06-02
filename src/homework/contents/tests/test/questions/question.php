<?php
global $route;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBCrud\View\HTML\displayOperationToolbar($route, 2);
?>
<?php
global $crudInterface;

\SBData\View\HTML\displayEditableForm($crudInterface->form,
	"Submit",
	"One or more fields are incorrectly specified and marked with a red color!",
	"This field is incorrectly specified!");
?>
