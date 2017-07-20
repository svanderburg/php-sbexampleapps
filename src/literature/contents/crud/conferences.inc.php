<?php
require_once("data/view/html/table.inc.php");

global $crudModel, $authorizationManager;
?>
<p>
	<?php
	if($authorizationManager->authenticated)
	{
		?>
		<a href="?__operation=create_conference">Add conference</a>
		<?php
	}
	?>
</p>
<?php
if($authorizationManager->authenticated)
	displaySemiEditableTable($crudModel->table);
else
	displayTable($crudModel->table);
?>
