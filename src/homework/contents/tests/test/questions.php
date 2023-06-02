<?php
global $route, $table;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 2);
?>
<div class="tabpage">
	<?php
	\SBCrud\View\HTML\displayOperationToolbar($route, 2);
	\SBData\View\HTML\displaySemiEditableTable($table);
	?>
</div>
