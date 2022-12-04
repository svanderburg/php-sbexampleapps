<?php
global $crudInterface, $authorizationManager;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 4);
?>
<div class="tabpage">
	<?php
	if($authorizationManager->authenticated)
	{
		\SBData\View\HTML\displayEditableForm($crudInterface->addAuthorForm,
			"Add author",
			"One or more fields are incorrectly specified and marked with a red color!",
			"This field is incorrectly specified!");
	}

	if($crudInterface->table !== null)
	{
		if($authorizationManager->authenticated)
			\SBData\View\HTML\displaySemiEditableTable($crudInterface->table, "No authors", "author-row");
		else
			\SBData\View\HTML\displayTable($crudInterface->table, "No authors");
	}
	?>
</div>
