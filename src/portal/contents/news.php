<?php
use SBExampleApps\Portal\Model\Entity\NewsMessageEntity;

global $route, $dbh, $stmt, $authorizationManager, $baseURL;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);

if($authorizationManager->authenticated)
{
	?>
	<p>
		<a href="?__operation=create_newsmessage">Add news message</a>
	</p>
	<?php
}

while(($row = $stmt->fetch()) !== false)
	\SBExampleApps\Portal\View\displayNewsMessage($row, $authorizationManager);
?>
<p>
	<?php
	$page = $GLOBALS["requestParameters"]["page"];
	if($page > 0)
	{
		?>
		<a style="float: left;" href="<?php print($_SERVER["PHP_SELF"]."?".http_build_query(array("page" => $page - 1), "", "&amp;", PHP_QUERY_RFC3986)); ?>">&laquo; Previous</a>
		<?php
	}
	
	$stmt = NewsMessageEntity::queryNumOfNewsMessages($dbh);

	if(($row = $stmt->fetch()) !== false)
	{
		if(($page + 1) * 10 < intval($row[0]))
		{
			?>
			<a style="float: right;" href="<?php print($_SERVER["PHP_SELF"]."?".http_build_query(array("page" => $page + 1), "", "&amp;", PHP_QUERY_RFC3986)); ?>">Next &raquo;</a>
			<?php
		}
	}
	?>
</p>

<p style="clear: both; text-align: right;"><a href="<?php print($baseURL); ?>/rss.php"><img src="<?php print($baseURL); ?>/image/rss.png" alt="RSS feed"></a></p>
