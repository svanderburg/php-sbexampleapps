<?php
require_once("data/view/html/form.inc.php");
require_once("editor/view/html/htmleditorfield.inc.php");
require_once("view/newsmessage.inc.php");

global $crudModel, $authorizationManager;

$newsURL = $_SERVER["SCRIPT_NAME"]."/news";
?>
<p>
	<a href="<?php print($newsURL); ?>">&laquo; News</a>
	<?php
	if($authorizationManager->authenticated)
	{
		?>
		| <a href="<?php print($newsURL); ?>?__operation=create_newsmessage">Add news message</a>
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
	?>
	<script type="text/javascript">
	sbeditor.initEditors();
	</script>
	<?php
}
else
{
	$newsMessage = $crudModel->form->exportValues();
	displayNewsMessage($newsMessage, $authorizationManager);
}
?>
