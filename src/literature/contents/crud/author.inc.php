<?php
require_once("data/view/html/form.inc.php");

global $crudModel, $authorizationManager;

$authorsURL = $_SERVER["SCRIPT_NAME"]."/authors";
?>
<p>
	<a href="<?php print($authorsURL); ?>">&laquo; Authors</a>
	<?php
	if($authorizationManager->authenticated)
	{
		?>
		| <a href="<?php print($authorsURL); ?>?__operation=create_author">Add author</a>
		<?php
	}
	?>
</p>
<?php
if($authorizationManager->authenticated)
{
	displayEditableForm($crudModel->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}
else
	displayForm($crudModel->form);
?>
