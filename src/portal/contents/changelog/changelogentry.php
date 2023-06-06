<?php
global $crudInterface, $authorizationManager;

if($authorizationManager->authenticated)
{
	\SBCrud\View\HTML\displayOperationToolbar($route);
	\SBData\View\HTML\displayEditableForm($crudInterface->form);
}
else
	\SBData\View\HTML\displayForm($crudInterface->form);
?>
