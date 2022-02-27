<?php
namespace SBExampleApps\Portal\Model;
use SBGallery\Model\GalleryPermissionChecker;
use SBExampleApps\Auth\Model\AuthorizationManager;

class MyGalleryPermissionChecker implements GalleryPermissionChecker
{
	private AuthorizationManager $authorizationManager;

	public function __construct(AuthorizationManager $authorizationManager)
	{
		$this->authorizationManager = $authorizationManager;
	}

	public function checkWritePermissions(): bool
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
