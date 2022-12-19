<?php
namespace SBExampleApps\CMSGallery\Model\Page;
use PDO;
use SBGallery\Model\Gallery;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\Page\TraversableGalleryPage;
use SBGallery\Model\Page\Content\GalleryContents;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\CMSGallery\Model\MyGallery;
use SBExampleApps\CMSGallery\Model\MyGalleryPermissionChecker;

class MyGalleryPage extends TraversableGalleryPage
{
	private AuthorizationManager $authorizationManager;

	public function __construct(AuthorizationManager $authorizationManager, PDO $dbh)
	{
		parent::__construct($dbh, "Gallery", new GalleryContents(null, "contents", "HTML", array("gallery.css"), array(), "gallery.php"));
		$this->authorizationManager = $authorizationManager;
	}

	public function constructGallery(PDO $dbh): Gallery
	{
		return new MyGallery($dbh);
	}

	public function constructGalleryPermissionChecker(): GalleryPermissionChecker
	{
		return new MyGalleryPermissionChecker($this->authorizationManager);
	}
}
?>
