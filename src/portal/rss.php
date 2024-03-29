<?php
require_once("vendor/autoload.php");

use SBLayout\Model\Page\Page;
use SBExampleApps\Portal\Model\Entity\NewsMessageEntity;

require_once("includes/config.php");

$dbh = new PDO($config["dbDsn"], $config["dbUsername"], $config["dbPassword"], array(
	//PDO::ATTR_PERSISTENT => true
));

try
{
	$stmt = NewsMessageEntity::queryAll($dbh, 0);
	$stmt2 = NewsMessageEntity::queryLatestDate($dbh);

	header("Content-Type: application/rss+xml");
	$baseURL = (array_key_exists("HTTPS", $_SERVER) != "" ? "https://" : "http://").$_SERVER["SERVER_NAME"].Page::computeBaseURL();
	print('<?xml version="1.0" encoding="UTF-8" ?>');
	?>
<rss version="2.0">
	<channel>
		<title>News messages</title>
		<description>Example RSS feed for the news messages</description>
		<link><?= $baseURL ?></link>
		<?php
		if(($latestDateRow = $stmt2->fetch()) !== false)
		{
			$formattedDate = date('r', strtotime($latestDateRow[0]));
			?>
			<lastBuildDate><?= $formattedDate ?></lastBuildDate>
			<pubDate><?= $formattedDate ?></pubDate>
			<?php
		}
		?>
		<?php
		while(($row = $stmt->fetch()) !== false)
		{
			?>
			<item>
				<title><?= $row["Title"] ?></title>
				<description><![CDATA[<?= $row["Message"] ?>]]></description>
				<link><?= $baseURL ?>/index.php/news/<?= $row["MESSAGE_ID"] ?></link>
				<pubDate><?= date('r', strtotime($row["Date"])) ?></pubDate>
			</item>
			<?php
		}
		?>
	</channel>
</rss>
<?php
}
catch(Exception $ex)
{
	header("HTTP/1.1 404 Not Found");
	header("Content-Type: text/plain");
	?>
	Error: <?= $ex->getMessage() ?>
	<?php
}
?>
