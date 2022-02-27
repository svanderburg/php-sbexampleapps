<?php
namespace SBExampleApps\CMS\Model\Page;
use PDO;
use SBGallery\Model\Gallery;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\Page\GalleryPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\CMS\Model\MyGallery;
use SBExampleApps\CMS\Model\MyGalleryPermissionChecker;

class MyGalleryPage extends GalleryPage
{
	private AuthorizationManager $authorizationManager;

	private PDO $dbh;

	public function __construct(AuthorizationManager $authorizationManager, PDO $dbh)
	{
		parent::__construct("Gallery");
		$this->authorizationManager = $authorizationManager;
		$this->dbh = $dbh;
	}
	
	public function constructGallery(): Gallery
	{
		return new MyGallery($this->dbh);
	}

	public function constructGalleryPermissionChecker(): GalleryPermissionChecker
	{
		return new MyGalleryPermissionChecker($this->authorizationManager);
	}
}
?>
