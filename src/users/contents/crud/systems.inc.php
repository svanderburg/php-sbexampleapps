<?php
require_once("data/view/html/table.inc.php");
?>
<p>
	<a href="?__operation=create_system">Add system</a>
</p>
<?php
function deleteSystemLink(Form $form)
{
	return $_SERVER["SCRIPT_NAME"]."/systems/".$form->fields["SYSTEM_ID"]->value."?__operation=delete_system";
}

global $crudModel;

displayTable($crudModel->table, "deleteSystemLink");
?>
