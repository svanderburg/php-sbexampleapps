<?php
global $crudModel, $authorizationManager;
?>
<p>
	<?php
	if($authorizationManager->authenticated)
	{
		?>
		<a href="?__operation=create_publisher">Add publisher</a>
		<?php
	}
	?>
</p>
<?php
if($authorizationManager->authenticated)
	\SBData\View\HTML\displaySemiEditableTable($crudModel->table);
else
	\SBData\View\HTML\displayTable($crudModel->table);
?>
