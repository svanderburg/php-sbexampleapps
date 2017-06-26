<?php
require_once("data/view/html/table.inc.php");
require_once("view/newsmessage.inc.php");

global $crudModel, $authorizationManager, $baseURL;
?>
<p>
	<?php
	if($authorizationManager->authenticated)
	{
		?>
		<a href="?__operation=create_newsmessage">Add news message</a>
		<?php
	}
	?>
</p>
<?php
while(($row = $crudModel->stmt->fetch()) !== false)
	displayNewsMessage($row, $authorizationManager);
?>

<p>
	<?php
	if($crudModel->page > 0)
	{
		?>
		<a style="float: left;" href="<?php print($_SERVER["PHP_SELF"]."?page=".($crudModel->page - 1)); ?>">&laquo; Previous</a>
		<?php
	}
	
	$stmt = NewsMessageEntity::queryNumOfNewsMessages($crudModel->dbh);

	if(($row = $stmt->fetch()) !== false)
	{
		if(($crudModel->page + 1) * 10 <= intval($row[0]))
		{
			?>
			<a style="float: right;" href="<?php print($_SERVER["PHP_SELF"]."?page=".($crudModel->page + 1)); ?>">Next &raquo;</a>
			<?php
		}
	}
	?>
</p>

<p style="clear: both; text-align: right;"><a href="<?php print($baseURL); ?>/rss.php"><img src="<?php print($baseURL); ?>/image/rss.png" alt="RSS feed"></a></p>
