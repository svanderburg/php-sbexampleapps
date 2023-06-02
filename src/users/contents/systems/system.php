<?php
global $route, $crudInterface;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBCrud\View\HTML\displayOperationToolbar($route);
\SBData\View\HTML\displayEditableForm($crudInterface->form,
	"Submit",
	"One or more fields are incorrectly specified and marked with a red color!",
	"This field is incorrectly specified!");
?>
