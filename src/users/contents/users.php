<?php
global $route, $table;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
?>
<p>
	<a href="?__operation=create_user">Add user</a>
</p>
<?php
\SBData\View\HTML\displaySemiEditableTable($table);
?>
