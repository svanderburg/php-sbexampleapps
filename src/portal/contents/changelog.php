<?php
use SBCrud\Model\RouteUtils;

global $route, $table, $submittedForm, $authorizationManager;

if($authorizationManager->authenticated)
{
	\SBCrud\View\HTML\displayOperationToolbar($route);
	\SBData\View\HTML\displayEditableTable($table, $submittedForm);
}
else
	\SBData\View\HTML\displayTable($table);
?>
