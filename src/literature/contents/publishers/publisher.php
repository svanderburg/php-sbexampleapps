<?php
global $crudInterface, $authorizationManager;

$publishersURL = $_SERVER["SCRIPT_NAME"]."/publishers";
?>
<p>
	<a href="<?= $publishersURL ?>">&laquo; Publishers</a>
	<?php
	if($authorizationManager->authenticated)
	{
		?>
		| <a href="<?= $publishersURL ?>?__operation=create_publisher">Add publisher</a>
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
