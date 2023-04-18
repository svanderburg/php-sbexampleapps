<?php
namespace SBExampleApps\Portal\Model\Page;
use PDO;
use SBGallery\Model\Page\GalleryPage;
use SBGallery\Model\Page\Content\GalleryContents;
use SBGallery\Model\Page\Settings\GalleryPageSettings;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Portal\Model\MyGalleryPermissionChecker;

class MyGalleryPage extends GalleryPage
{
	public function __construct(AuthorizationManager $authorizationManager, PDO $dbh)
	{
		parent::__construct($dbh, new GalleryPageSettings("gallery"), new MyGalleryPermissionChecker($authorizationManager), new GalleryContents(null, "contents", "HTML", array("gallery.css")));
	}
}
?>
