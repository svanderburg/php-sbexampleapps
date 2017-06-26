<?php
require_once("data/view/html/table.inc.php");

global $crudModel, $authorizationManager;
?>
<p>
	<?php
	if($authorizationManager->authenticated)
	{
		?>
		<a href="?__operation=create_publisher">Add publisher</a>
		<?php
	}
	?>
</p>
<?php
if($authorizationManager->authenticated)
{
	function deletePublisherLink(Form $form)
	{
		return $_SERVER["SCRIPT_NAME"]."/publishers/".$form->fields["PUBLISHER_ID"]->value."?__operation=delete_publisher";
	}

	displayTable($crudModel->table, "deletePublisherLink");
}
else
	displayTable($crudModel->table);
?>
