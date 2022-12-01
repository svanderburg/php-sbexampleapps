<?php
global $crudInterface, $authorizationManager;

$conferencesURL = $_SERVER["SCRIPT_NAME"]."/conferences";
?>
<p>
	<a href="<?= $conferencesURL ?>">&laquo; Conferences</a>
	<?php
	if($authorizationManager->authenticated)
	{
		?>
		| <a href="<?= $conferencesURL ?>?__operation=create_conference">Add conference</a>
		| <a href="<?= $_SERVER["PHP_SELF"] ?>/papers?__operation=create_paper">Add paper</a>
		<?php
	}
	?>
</p>
<?php
/* Display conference properties */
if($authorizationManager->authenticated)
{
	\SBData\View\HTML\displayEditableForm($crudInterface->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}
else
	\SBData\View\HTML\displayForm($crudInterface->form);

/* Display editors section */
if($crudInterface->addEditorForm !== null || $crudInterface->editorsTable !== null)
{
	?>
	<a name="editors"></a>
	<h2>Editors</h2>
	<?php
}

if($authorizationManager->authenticated && $crudInterface->addEditorForm !== null)
{
	\SBData\View\HTML\displayEditableForm($crudInterface->addEditorForm,
		"Add editor",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}

if($crudInterface->editorsTable !== null)
{
	if($authorizationManager->authenticated)
		\SBData\View\HTML\displaySemiEditableTable($crudInterface->editorsTable, "No editors", "editor-row");
	else
		\SBData\View\HTML\displayTable($crudInterface->editorsTable, "No editors");
}

/* Display papers section */
if($crudInterface->papersTable !== null)
{
	?>
	<h2>Papers</h2>
	<?php
	if($authorizationManager->authenticated)
		\SBData\View\HTML\displaySemiEditableTable($crudInterface->papersTable, "No papers", "paper-row");
	else
		\SBData\View\HTML\displayTable($crudInterface->papersTable, "No papers");
}
?>
