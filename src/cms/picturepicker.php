<?php
require_once("vendor/autoload.php");

use SBGallery\Model\Gallery;
use SBExampleApps\CMS\Model\Page\Settings\MyGalleryPageSettings;

require_once("includes/config.php");

$dbh = new PDO($config["dbDsn"], $config["dbUsername"], $config["dbPassword"], array(
	//PDO::ATTR_PERSISTENT => true
));

$galleryPageSettings = new MyGalleryPageSettings();
$myGallery = new Gallery($dbh, $galleryPageSettings->gallerySettings);
\SBGallery\View\HTML\displayPicturePickerPage($myGallery, "Gallery", array("styles/default.css", "styles/gallery.css"));
?>
