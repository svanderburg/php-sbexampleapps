<?php
require_once("data/view/html/table.inc.php");
?>
<p>
	<a href="?__operation=create_user">Add user</a>
</p>
<?php
global $crudModel;

displaySemiEditableTable($crudModel->table);
?>
