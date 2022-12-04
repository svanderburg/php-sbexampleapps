<?php
global $route, $table, $authorizationManager;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);

if($authorizationManager->authenticated)
{
	?>
	<p>
		<a href="?__operation=create_author">Add author</a>
	</p>
	<?php
}

if($authorizationManager->authenticated)
	\SBData\View\HTML\displaySemiEditableTable($table);
else
	\SBData\View\HTML\displayTable($table);
?>
