<?php
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
	\SBData\View\HTML\displaySemiEditableTable($crudModel->table, true);
else
	\SBData\View\HTML\displayTable($crudModel->table);
?>
