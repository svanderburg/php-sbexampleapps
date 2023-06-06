<?php
global $route, $table, $authorizationManager;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 2);
?>
<div class="tabpage">
	<?php
	if($authorizationManager->authenticated)
	{
		\SBCrud\View\HTML\displayOperationToolbar($route, 2);
		\SBData\View\HTML\displaySemiEditableTable($table);
	}
	else
		\SBData\View\HTML\displayTable($table);
	?>
</div>
