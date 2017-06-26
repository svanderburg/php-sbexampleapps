<?php
require_once("data/view/html/form.inc.php");
require_once("data/view/html/table.inc.php");

$usersURL = $_SERVER["SCRIPT_NAME"]."/users";
?>
<p>
	<a href="<?php print($usersURL); ?>">&laquo; Users</a> |
	<a href="<?php print($usersURL); ?>?__operation=create_user">Add user</a>
</p>
<?php
function deleteUserSystemLink(Form $form)
{
	return $_SERVER['PHP_SELF']."?__operation=delete_user_system&amp;SYSTEM_ID=".$form->fields["SYSTEM_ID"]->value;
}

global $crudModel;

displayEditableForm($crudModel->form,
	"Submit",
	"One or more fields are incorrectly specified and marked with a red color!",
	"This field is incorrectly specified!");

if($crudModel->addSystemForm !== null || $crudModel->table !== null)
{
	?>
	<h2>Systems</h2>
	<?php
}

if($crudModel->addSystemForm !== null)
{
	displayEditableForm($crudModel->addSystemForm,
		"Add system",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}

if($crudModel->table !== null)
	displayTable($crudModel->table, "deleteUserSystemLink");
?>
