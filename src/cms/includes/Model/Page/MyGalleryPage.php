<?php
namespace SBExampleApps\CMS\Model\Page;
use PDO;
use SBGallery\Model\Page\Content\GalleryContents;
use SBPageManager\Model\Page\IntegratedGalleryPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\CMS\Model\MyPagePermissionChecker;
use SBExampleApps\CMS\Model\Page\Settings\MyGalleryPageSettings;

class MyGalleryPage extends IntegratedGalleryPage
{
	public function __construct(AuthorizationManager $authorizationManager, PDO $dbh)
	{
		parent::__construct($dbh, new MyGalleryPageSettings(), new MyPagePermissionChecker($authorizationManager), new GalleryContents(null, "contents", "HTML", array("gallery.css")));
	}
}
?>
