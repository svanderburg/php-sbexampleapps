<p>
	<a href="?__operation=create_test">Add test</a>
</p>
<?php
global $crudModel;

\SBData\View\HTML\displaySemiEditableTable($crudModel->table, true);
?>
