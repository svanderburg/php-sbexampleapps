<?php
namespace SBExampleApps\CMS\Model;
use SBPageManager\Model\PagePermissionChecker;
use SBExampleApps\Auth\Model\AuthorizationManager;

class MyPagePermissionChecker implements PagePermissionChecker
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
