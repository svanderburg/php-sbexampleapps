<?php
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
	\SBData\View\HTML\displayEditableForm($crudModel->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}
else
	\SBData\View\HTML\displayForm($crudModel->form);

/* Display editors section */
if($crudModel->addEditorForm !== null || $crudModel->editorsTable !== null)
{
	?>
	<h2>Editors</h2>
	<?php
}

if($authorizationManager->authenticated && $crudModel->addEditorForm !== null)
{
	\SBData\View\HTML\displayEditableForm($crudModel->addEditorForm,
		"Add editor",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}

if($crudModel->editorsTable !== null)
{
	if($authorizationManager->authenticated)
		\SBData\View\HTML\displaySemiEditableTable($crudModel->editorsTable);
	else
		\SBData\View\HTML\displayTable($crudModel->editorsTable);
}

/* Display papers section */
if($crudModel->papersTable !== null)
{
	?>
	<h2>Papers</h2>
	<?php
	if($authorizationManager->authenticated)
		\SBData\View\HTML\displaySemiEditableTable($crudModel->papersTable);
	else
		\SBData\View\HTML\displayTable($crudModel->papersTable);
}
?>
