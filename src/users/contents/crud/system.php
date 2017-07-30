<?php
$systemsURL = $_SERVER["SCRIPT_NAME"]."/systems";
?>
<p>
	<a href="<?php print($systemsURL); ?>">&laquo; Systems</a> |
	<a href="<?php print($systemsURL); ?>?__operation=create_system">Add system</a>
</p>
<?php
global $crudModel;

\SBData\View\HTML\displayEditableForm($crudModel->form,
	"Submit",
	"One or more fields are incorrectly specified and marked with a red color!",
	"This field is incorrectly specified!");
?>
