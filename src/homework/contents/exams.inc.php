<p>
Please pick a test:
</p>
<?php
require_once("data/view/html/form.inc.php");

global $form;
displayEditableForm($form,
	"Submit",
	"One or more fields are incorrectly specified and marked with a red color!",
	"This field is incorrectly specified!");
?>
