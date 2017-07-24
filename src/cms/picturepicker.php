<?php
set_include_path("./lib/sblayout:./lib/sbdata:./lib/sbgallery:./lib/sbeditor:./includes");

require_once("config.inc.php");

require_once("includes/model/MyGallery.class.php");
require_once("gallery/view/html/picturepickerpage.inc.php");

$dbh = new PDO($config["dbDsn"], $config["dbUsername"], $config["dbPassword"], array(
	PDO::ATTR_PERSISTENT => true
));

$myGallery = new MyGallery($dbh);
displayPicturePickerPage($myGallery, "Gallery", array("lib/layout/styles/default.css", "lib/layout/styles/gallery.css"));
?>
