<?php
require_once("data/view/html/form.inc.php");

global $crudModel, $authorizationManager;

$publishersURL = $_SERVER["SCRIPT_NAME"]."/publishers";
?>
<p>
	<a href="<?php print($publishersURL); ?>">&laquo; Publishers</a>
	<?php
	if($authorizationManager->authenticated)
	{
		?>
		| <a href="<?php print($publishersURL); ?>?__operation=create_publisher">Add publisher</a>
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
