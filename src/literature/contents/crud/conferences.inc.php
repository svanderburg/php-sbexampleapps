<?php
require_once("data/view/html/table.inc.php");

global $crudModel, $authorizationManager;
?>
<p>
	<?php
	if($authorizationManager->authenticated)
	{
		?>
		<a href="?__operation=create_conference">Add conference</a>
		<?php
	}
	?>
</p>
<?php
if($authorizationManager->authenticated)
{
	function deleteConferenceLink(Form $form)
	{
		return $_SERVER["SCRIPT_NAME"]."/conferences/".$form->fields["CONFERENCE_ID"]->value."?__operation=delete_conference";
	}

	displayTable($crudModel->table, "deleteConferenceLink");
}
else
	displayTable($crudModel->table);
?>
