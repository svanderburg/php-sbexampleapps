<?php
$testsURL = $_SERVER["SCRIPT_NAME"]."/tests";
?>
<p>
	<a href="<?= $testsURL ?>">&laquo; Tests</a> |
	<a href="<?= $testsURL ?>?__operation=create_test">Add test</a>
	<?php
	if(array_key_exists("query", $GLOBALS) && array_key_exists("testId", $GLOBALS["query"]))
	{
		?>
		| <a href="<?= $_SERVER["PHP_SELF"]."/questions" ?>?__operation=create_question">Add question</a>
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

if($crudInterface->table !== null)
{
	?>
	<h2>Questions</h2>
	<?php
	\SBData\View\HTML\displaySemiEditableTable($crudInterface->table);
}
?>
