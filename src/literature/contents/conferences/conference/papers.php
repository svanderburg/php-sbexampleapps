<?php
global $route, $table, $authorizationManager;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 2);
?>
<div class="tabpage">
	<?php
	if($authorizationManager->authenticated)
	{
		?>
		<p>
			<a href="?__operation=create_paper">Add paper</a>
		</p>
		<?php
	}

	if($authorizationManager->authenticated)
		\SBData\View\HTML\displaySemiEditableTable($table, "No papers", "paper-row");
	else
		\SBData\View\HTML\displayTable($table, "No papers");
	?>
</div>
