<?php
$usersURL = $_SERVER["SCRIPT_NAME"]."/users";
?>
<p>
	<a href="<?php print($usersURL); ?>">&laquo; Users</a> |
	<a href="<?php print($usersURL); ?>?__operation=create_user">Add user</a>
</p>
<?php
global $crudModel;

\SBData\View\HTML\displayEditableForm($crudModel->form,
	"Submit",
	"One or more fields are incorrectly specified and marked with a red color!",
	"This field is incorrectly specified!");

if($crudModel->addSystemForm !== null || $crudModel->table !== null)
{
	?>
	<a name="systems"></a>
	<h2>Systems</h2>
	<?php
}

if($crudModel->addSystemForm !== null)
{
	\SBData\View\HTML\displayEditableForm($crudModel->addSystemForm,
		"Add system",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}

if($crudModel->table !== null)
	\SBData\View\HTML\displaySemiEditableTable($crudModel->table, "No items", "user-system-row");
?>
