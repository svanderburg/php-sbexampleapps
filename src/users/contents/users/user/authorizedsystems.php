<?php
global $route, $addSystemForm, $table;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 2);
?>
<div class="tabpage">
	<?php
	\SBData\View\HTML\displayEditableForm($addSystemForm);
	\SBData\View\HTML\displaySemiEditableTable($table, "No items");
	?>
</div>
