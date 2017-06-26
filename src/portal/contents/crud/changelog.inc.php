<?php
require_once("data/view/html/table.inc.php");
require_once("data/view/html/form.inc.php");

global $crudModel, $authorizationManager;

if($authorizationManager->authenticated)
{
	if($crudModel->addEntryForm === null)
	{
		?>
		<p><a href="<?php print($_SERVER["PHP_SELF"]); ?>?__operation=create_changelogentry">Add entry</a></p>
		<?php
		function deleteChangeLogLink(Form $form)
		{
			return "?__operation=remove_changelogentry&amp;LOG_ID=".$form->fields["LOG_ID"]->value."&amp;__id=".$form->fields["__id"]->value;
		}

		displayEditableTable($crudModel->table, $crudModel->submittedForm, "deleteChangeLogLink");
	}
	else
	{
		displayEditableForm($crudModel->addEntryForm,
			"Submit",
			"One or more fields are incorrectly specified and marked with a red color!",
			"This field is incorrectly specified!");
	}
}
else
	displayTable($crudModel->table);
?>
