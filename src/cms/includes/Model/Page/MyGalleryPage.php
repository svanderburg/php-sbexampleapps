<?php
namespace SBExampleApps\CMS\Model\Page;
use PDO;
use SBGallery\Model\Page\GalleryPage;
use SBGallery\Model\Page\Content\GalleryContents;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\CMS\Model\MyGalleryPermissionChecker;
use SBExampleApps\CMS\Model\Page\Settings\MyGalleryPageSettings;

class MyGalleryPage extends GalleryPage
{
	public function __construct(AuthorizationManager $authorizationManager, PDO $dbh)
	{
		parent::__construct($dbh, new MyGalleryPageSettings(), new MyGalleryPermissionChecker($authorizationManager), new GalleryContents(null, "contents", "HTML", array("gallery.css")));
	}
}
?>
