<?php
require_once("data/view/html/table.inc.php");
?>
<p>
	<a href="?__operation=create_test">Add test</a>
</p>
<?php
global $crudModel;

displayTable($crudModel->table);
?>
