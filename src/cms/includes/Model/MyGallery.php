<?php
namespace SBExampleApps\CMS\Model;
use PDO;
use SBLayout\Model\Page\Page;
use SBGallery\Model\Gallery;

class MyGallery extends Gallery
{
	function __construct(PDO $dbh)
	{
		$baseURL = Page::computeBaseURL();

		$editorSettings = array(
			"id" => "editor1",
			"iframePage" => $baseURL."/iframepage.html",
			"iconsPath" => $baseURL."/image/editor",
			"width" => 60,
			"height" => 20
		);

		parent::__construct($dbh, $baseURL."/gallery", $baseURL."/album.php", $baseURL."/picture.php", $baseURL."/multiplepictures.php", $baseURL."/image/gallery", "gallery", 160, 120, 1280, 960, null, null, null, $editorSettings);
	}
}
?>
