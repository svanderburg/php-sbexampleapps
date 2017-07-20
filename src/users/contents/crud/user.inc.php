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
	displaySemiEditableTable($crudModel->table);
?>
