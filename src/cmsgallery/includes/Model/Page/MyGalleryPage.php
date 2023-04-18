<?php
namespace SBExampleApps\CMSGallery\Model\Page;
use PDO;
use SBGallery\Model\Page\TraversableGalleryPage;
use SBGallery\Model\Page\Content\GalleryContents;
use SBGallery\Model\Page\Settings\GalleryPageSettings;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\CMSGallery\Model\MyGalleryPermissionChecker;

class MyGalleryPage extends TraversableGalleryPage
{
	public function __construct(AuthorizationManager $authorizationManager, PDO $dbh)
	{
		parent::__construct($dbh, new GalleryPageSettings("gallery"), new MyGalleryPermissionChecker($authorizationManager), new GalleryContents(null, "contents", "HTML", array("gallery.css"), array(), "gallery.php"));
	}
}
?>
