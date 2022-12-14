<?php
use SBCrud\Model\RouteUtils;

global $table, $submittedForm, $authorizationManager;

if($authorizationManager->authenticated)
{
	?>
	<p><a href="<?= RouteUtils::composeSelfURL() ?>?__operation=create_changelogentry">Add entry</a></p>
	<?php
	\SBData\View\HTML\displayEditableTable($table, $submittedForm);
}
else
	\SBData\View\HTML\displayTable($table);
?>
