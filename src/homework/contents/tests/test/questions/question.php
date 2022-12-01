<p>
	<?php
	if(array_key_exists("testId", $GLOBALS["query"]))
	{
		$testURL = $_SERVER["SCRIPT_NAME"]."/tests/".$GLOBALS["query"]["testId"];
		?>
		<a href="<?= $testURL ?>">&laquo; Test: <?= $GLOBALS["query"]["testId"] ?></a> |
		<a href="<?= $testURL."/questions" ?>?__operation=create_question">Add question</a>
		<?php
	}
	?>
</p>
<?php
global $crudInterface;

\SBData\View\HTML\displayEditableForm($crudInterface->form,
	"Submit",
	"One or more fields are incorrectly specified and marked with a red color!",
	"This field is incorrectly specified!");
?>
