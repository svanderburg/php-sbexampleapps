<?php
global $crudInterface, $authorizationManager;

if($authorizationManager->authenticated)
{
	\SBCrud\View\HTML\displayOperationToolbar($route);
	\SBData\View\HTML\displayEditableForm($crudInterface->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}
else
	\SBData\View\HTML\displayForm($crudInterface->form);
?>
