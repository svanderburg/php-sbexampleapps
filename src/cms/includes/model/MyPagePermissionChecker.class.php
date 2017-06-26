<?php
require_once("pagemanager/model/PagePermissionChecker.interface.php");

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
