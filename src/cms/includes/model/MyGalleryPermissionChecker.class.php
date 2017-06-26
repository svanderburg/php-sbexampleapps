<?php
require_once("gallery/model/GalleryPermissionChecker.interface.php");

class MyGalleryPermissionChecker implements GalleryPermissionChecker
{
	private $authorizationManager;

	public function __construct(AuthorizationManager $authorizationManager)
	{
		$this->authorizationManager = $authorizationManager;
	}

	public function checkWritePermissions()
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
