<?php
global $route, $addEditorForm, $table, $authorizationManager;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 2);
?>
<div class="tabpage">
	<?php
	if($authorizationManager->authenticated)
	{
		\SBData\View\HTML\displayEditableForm($addEditorForm,
			"Add editor",
			"One or more fields are incorrectly specified and marked with a red color!",
			"This field is incorrectly specified!");

		\SBData\View\HTML\displaySemiEditableTable($table, "No editors");
	}
	else
		\SBData\View\HTML\displayTable($table, "No editors");
	?>
</div>
