<?php
global $addAuthorForm, $table, $authorizationManager;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 4);
?>
<div class="tabpage">
	<?php
	if($authorizationManager->authenticated)
	{
		\SBData\View\HTML\displayEditableForm($addAuthorForm,
			"Add author",
			"One or more fields are incorrectly specified and marked with a red color!",
			"This field is incorrectly specified!");
	}

	if($table !== null)
	{
		if($authorizationManager->authenticated)
			\SBData\View\HTML\displaySemiEditableTable($table, "No authors");
		else
			\SBData\View\HTML\displayTable($table, "No authors");
	}
	?>
</div>
