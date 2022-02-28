<?php
require_once("vendor/autoload.php");

use SBExampleApps\CMS\Model\MyGallery;

require_once("includes/config.php");

$dbh = new PDO($config["dbDsn"], $config["dbUsername"], $config["dbPassword"], array(
	//PDO::ATTR_PERSISTENT => true
));

$myGallery = new MyGallery($dbh);
\SBGallery\View\HTML\displayPicturePickerPage($myGallery, "Gallery", array("styles/default.css", "styles/gallery.css"));
?>
