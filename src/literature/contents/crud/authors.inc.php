<?php
require_once("data/view/html/table.inc.php");

global $crudModel, $authorizationManager;
?>
<p>
	<?php
	if($authorizationManager->authenticated)
	{
		?>
		<a href="?__operation=create_author">Add author</a>
		<?php
	}
	?>
</p>
<?php
if($authorizationManager->authenticated)
{
	function deleteAuthorLink(Form $form)
	{
		return $_SERVER["SCRIPT_NAME"]."/authors/".$form->fields["AUTHOR_ID"]->value."?__operation=delete_author";
	}

	displayTable($crudModel->table, "deleteAuthorLink");
}
else
	displayTable($crudModel->table);
?>
