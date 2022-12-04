<?php
global $route, $crudInterface, $authorizationManager;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 2);
?>
<div class="tabpage">
	<?php
	if($authorizationManager->authenticated)
	{
		\SBData\View\HTML\displayEditableForm($crudInterface->addEditorForm,
			"Add editor",
			"One or more fields are incorrectly specified and marked with a red color!",
			"This field is incorrectly specified!");
	}

	if($authorizationManager->authenticated)
		\SBData\View\HTML\displaySemiEditableTable($crudInterface->table, "No editors");
	else
		\SBData\View\HTML\displayTable($crudInterface->table, "No editors");
	?>
</div>
