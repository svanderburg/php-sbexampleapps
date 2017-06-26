<?php
set_include_path("./lib/sblayout:./lib/sbdata:./lib/sbgallery:./lib/sbeditor");

require_once("includes/model/MyGallery.class.php");
require_once("gallery/view/html/picturepickerpage.inc.php");

$dbh = new PDO("mysql:host=localhost;dbname=cms", "root", "admin", array(
	PDO::ATTR_PERSISTENT => true
));

$myGallery = new MyGallery($dbh);
displayPicturePickerPage($myGallery, "Gallery", "lib/layout/styles/default.css");
?>
