<?php
require_once("data/view/html/form.inc.php");
require_once("data/view/html/table.inc.php");

global $crudModel, $authorizationManager;

$conferencesURL = $_SERVER["SCRIPT_NAME"]."/conferences";
?>
<p>
	<a href="<?php print($conferencesURL); ?>">&laquo; Conferences</a>
	<?php
	if($authorizationManager->authenticated)
	{
		?>
		| <a href="<?php print($conferencesURL); ?>?__operation=create_conference">Add conference</a>
		| <a href="<?php print($_SERVER["PHP_SELF"]); ?>?__operation=create_paper">Add paper</a>
		<?php
	}
	?>
</p>
<?php
/* Display conference properties */
if($authorizationManager->authenticated)
{
	displayEditableForm($crudModel->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}
else
	displayForm($crudModel->form);

/* Display editors section */
if($crudModel->addEditorForm !== null || $crudModel->editorsTable !== null)
{
	?>
	<h2>Editors</h2>
	<?php
}

if($authorizationManager->authenticated && $crudModel->addEditorForm !== null)
{
	displayEditableForm($crudModel->addEditorForm,
		"Add editor",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}

if($crudModel->editorsTable !== null)
{
	if($authorizationManager->authenticated)
		displaySemiEditableTable($crudModel->editorsTable);
	else
		displayTable($crudModel->editorsTable);
}

/* Display papers section */
if($crudModel->papersTable !== null)
{
	?>
	<h2>Papers</h2>
	<?php
	if($authorizationManager->authenticated)
		displaySemiEditableTable($crudModel->papersTable);
	else
		displayTable($crudModel->papersTable);
}
?>
