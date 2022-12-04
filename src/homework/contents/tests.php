<?php
\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
?>
<p>
	<a href="?__operation=create_test">Add test</a>
</p>
<?php
global $table;

\SBData\View\HTML\displaySemiEditableTable($table);
?>
