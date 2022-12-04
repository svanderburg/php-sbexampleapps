<?php
global $route, $crudInterface;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 2);
?>
<div class="tabpage">
	<?php
	\SBData\View\HTML\displayEditableForm($crudInterface->addSystemForm,
		"Add system",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");

	\SBData\View\HTML\displaySemiEditableTable($crudInterface->table, "No items");
	?>
</div>
