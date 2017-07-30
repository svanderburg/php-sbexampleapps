<?php
namespace SBExampleApps\CMS\Model\Page;
use PDO;
use SBGallery\Model\Page\GalleryPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\CMS\Model\MyGallery;
use SBExampleApps\CMS\Model\MyGalleryPermissionChecker;

class MyGalleryPage extends GalleryPage
{
	private $authorizationManager;

	private $dbh;

	public function __construct(AuthorizationManager $authorizationManager, PDO $dbh)
	{
		parent::__construct("Gallery");
		$this->authorizationManager = $authorizationManager;
		$this->dbh = $dbh;
	}
	
	public function constructGallery()
	{
		return new MyGallery($this->dbh);
	}

	public function constructGalleryPermissionChecker()
	{
		return new MyGalleryPermissionChecker($this->authorizationManager);
	}
}
?>
