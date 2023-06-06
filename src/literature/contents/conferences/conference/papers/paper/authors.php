<?php
global $addAuthorForm, $table, $authorizationManager;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 4);
?>
<div class="tabpage">
	<?php
	if($authorizationManager->authenticated)
		\SBData\View\HTML\displayEditableForm($addAuthorForm);

	if($table !== null)
	{
		if($authorizationManager->authenticated)
			\SBData\View\HTML\displaySemiEditableTable($table);
		else
			\SBData\View\HTML\displayTable($table);
	}
	?>
</div>
