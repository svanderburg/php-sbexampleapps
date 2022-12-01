<?php
global $table, $authorizationManager;
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
	\SBData\View\HTML\displaySemiEditableTable($table);
else
	\SBData\View\HTML\displayTable($table);
?>
