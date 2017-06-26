<?php
require_once("data/view/html/form.inc.php");
require_once("data/view/html/table.inc.php");

global $crudModel;

if($crudModel->table !== null)
	displayTable($crudModel->table);
else
{
	?>
	<p>
		With this function you can do a <strong>fuzzy search</strong> on the collection of papers.
		You can provide any keyword, such as a paper title, author name, conference name or publisher name.
	</p>
	<?php
	displayEditableForm($crudModel->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}
?>
