<?php
global $route, $table, $authorizationManager;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);

if($authorizationManager->authenticated)
{
	\SBCrud\View\HTML\displayOperationToolbar($route);
	\SBData\View\HTML\displaySemiEditableTable($table);
}
else
	\SBData\View\HTML\displayTable($table);
?>
