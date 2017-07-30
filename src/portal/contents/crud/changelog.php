<?php
global $crudModel, $authorizationManager;

if($authorizationManager->authenticated)
{
	if($crudModel->addEntryForm === null)
	{
		?>
		<p><a href="<?php print($_SERVER["PHP_SELF"]); ?>?__operation=create_changelogentry">Add entry</a></p>
		<?php
		\SBData\View\HTML\displayEditableTable($crudModel->table, $crudModel->submittedForm);
	}
	else
	{
		\SBData\View\HTML\displayEditableForm($crudModel->addEntryForm,
			"Submit",
			"One or more fields are incorrectly specified and marked with a red color!",
			"This field is incorrectly specified!");
	}
}
else
	\SBData\View\HTML\displayTable($crudModel->table);
?>
