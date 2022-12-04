<?php
\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 2);
?>
<div class="tabpage">
	<p><a href="?__operation=create_question">Add question</a></p>
	<?php
	global $table;
	\SBData\View\HTML\displaySemiEditableTable($table);
	?>
</div>
