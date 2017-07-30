<?php
global $crudModel;

if($crudModel->table !== null)
	\SBData\View\HTML\displayTable($crudModel->table);
else
{
	?>
	<p>
		With this function you can do a <strong>fuzzy search</strong> on the collection of papers.
		You can provide any keyword, such as a paper title, author name, conference name or publisher name.
	</p>
	<?php
	\SBData\View\HTML\displayEditableForm($crudModel->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}
?>
