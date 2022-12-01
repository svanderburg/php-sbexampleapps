<?php
namespace SBExampleApps\Portal\Model\Page;
use PDO;
use SBGallery\Model\Gallery;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\Page\GalleryPage;
use SBGallery\Model\Page\Content\GalleryContents;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Portal\Model\MyGallery;
use SBExampleApps\Portal\Model\MyGalleryPermissionChecker;

class MyGalleryPage extends GalleryPage
{
	private AuthorizationManager $authorizationManager;

	public function __construct(AuthorizationManager $authorizationManager, PDO $dbh)
	{
		parent::__construct($dbh, "Gallery", new GalleryContents(array(), "contents", "HTML", array("gallery.css")));
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
