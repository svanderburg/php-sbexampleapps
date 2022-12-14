<?php
global $route;
\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
?>
<p>
	<a href="?__operation=create_system">Add system</a>
</p>
<?php
global $table;

\SBData\View\HTML\displaySemiEditableTable($table);
?>
