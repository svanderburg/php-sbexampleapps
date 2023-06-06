<?php
global $route, $crudInterface, $authorizationManager;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 2);
?>
<div class="tabpage">
	<?php
	if($authorizationManager->authenticated)
	{
		\SBCrud\View\HTML\displayOperationToolbar($route);
		\SBData\View\HTML\displayEditableForm($crudInterface->form);
	}
	else
		\SBData\View\HTML\displayForm($crudInterface->form);
	?>
</div>
