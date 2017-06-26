<?php
require_once("data/view/html/form.inc.php");
?>
<p>
	<?php
	if(array_key_exists("testId", $GLOBALS["query"]))
	{
		$testURL = $_SERVER["SCRIPT_NAME"]."/tests/".$GLOBALS["query"]["testId"];
		?>
		<a href="<?php print($testURL); ?>">&laquo; Test: <?php print($GLOBALS["query"]["testId"]); ?></a> |
		<a href="<?php print($testURL); ?>?__operation=create_question">Add question</a>
		<?php
	}
	?>
</p>
<?php
global $crudModel;

displayEditableForm($crudModel->form,
	"Submit",
	"One or more fields are incorrectly specified and marked with a red color!",
	"This field is incorrectly specified!");
?>
