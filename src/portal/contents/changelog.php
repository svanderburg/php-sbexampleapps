<?php
global $table, $submittedForm, $authorizationManager;

if($authorizationManager->authenticated)
{
	?>
	<p><a href="<?php print($_SERVER["PHP_SELF"]); ?>?__operation=create_changelogentry">Add entry</a></p>
	<?php
	\SBData\View\HTML\displayEditableTable($table, $submittedForm);
}
else
	\SBData\View\HTML\displayTable($table);
?>
