<?php
$usersURL = $_SERVER["SCRIPT_NAME"]."/users";
?>
<p>
	<a href="<?php print($usersURL); ?>">&laquo; Users</a> |
	<a href="<?php print($usersURL); ?>?__operation=create_user">Add user</a>
</p>
<?php
global $crudInterface;

\SBData\View\HTML\displayEditableForm($crudInterface->form,
	"Submit",
	"One or more fields are incorrectly specified and marked with a red color!",
	"This field is incorrectly specified!");

if($crudInterface->addSystemForm !== null || $crudInterface->table !== null)
{
	?>
	<a name="systems"></a>
	<h2>Systems</h2>
	<?php
}

if($crudInterface->addSystemForm !== null)
{
	\SBData\View\HTML\displayEditableForm($crudInterface->addSystemForm,
		"Add system",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}

if($crudInterface->table !== null)
	\SBData\View\HTML\displaySemiEditableTable($crudInterface->table, "No items", "user-system-row");
?>
