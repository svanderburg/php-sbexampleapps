<?php
global $table, $form;

if($table !== null)
	\SBData\View\HTML\displayTable($table);
else
{
	?>
	<p>
		With this function you can do a <strong>fuzzy search</strong> on the collection of papers.
		You can provide any keyword, such as a paper title, author name, conference name or publisher name.
	</p>
	<?php
	\SBData\View\HTML\displayEditableForm($form);
}
?>
