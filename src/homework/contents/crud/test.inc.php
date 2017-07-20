<?php
require_once("data/view/html/form.inc.php");
require_once("data/view/html/table.inc.php");

$testsURL = $_SERVER["SCRIPT_NAME"]."/tests";
?>
<p>
	<a href="<?php print($testsURL); ?>">&laquo; Tests</a> |
	<a href="<?php print($testsURL); ?>?__operation=create_test">Add test</a>
	<?php
	if(array_key_exists("testId", $GLOBALS["query"]))
	{
		?>
		| <a href="?__operation=create_question">Add question</a>
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

if($crudModel->table !== null)
{
	?>
	<h2>Questions</h2>
	<?php
	displaySemiEditableTable($crudModel->table);
}
?>
