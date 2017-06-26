<?php
require_once("data/view/html/table.inc.php");
?>
<p>
	<a href="?__operation=create_user">Add user</a>
</p>
<?php
function deleteUserLink(Form $form)
{
	return $_SERVER["SCRIPT_NAME"]."/users/".$form->fields["Username"]->value."?__operation=delete_user";
}

global $crudModel;

displayTable($crudModel->table, "deleteUserLink");
//displayEditableTable($crudModel->table, null, "deleteUserLink");
?>
