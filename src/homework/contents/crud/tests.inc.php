<?php
require_once("data/view/html/table.inc.php");
?>
<p>
	<a href="?__operation=create_test">Add test</a>
</p>
<?php
function deleteTestLink(Form $form)
{
	return $_SERVER["SCRIPT_NAME"]."/tests/".$form->fields["TEST_ID"]->value."?__operation=delete_test";
}

global $crudModel;

displayTable($crudModel->table, "deleteTestLink");
?>
