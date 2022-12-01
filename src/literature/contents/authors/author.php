<?php
global $crudInterface, $authorizationManager;

$authorsURL = $_SERVER["SCRIPT_NAME"]."/authors";
?>
<p>
	<a href="<?= $authorsURL ?>">&laquo; Authors</a>
	<?php
	if($authorizationManager->authenticated)
	{
		?>
		| <a href="<?= $authorsURL ?>?__operation=create_author">Add author</a>
		<?php
	}
	?>
</p>
<?php
if($authorizationManager->authenticated)
{
	\SBData\View\HTML\displayEditableForm($crudInterface->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}
else
	\SBData\View\HTML\displayForm($crudInterface->form);
?>
