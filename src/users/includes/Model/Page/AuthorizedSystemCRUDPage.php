<?php
namespace SBExampleApps\Users\Model\Page;
use SBCrud\Model\Page\CRUDDetailPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Auth\Model\Page\RestrictedOperationPage;
use SBExampleApps\Users\Model\Page\Content\AuthorizedSystemContents;

class AuthorizedSystemCRUDPage extends CRUDDetailPage
{
	public AuthorizationManager $authorizationManager;

	public function __construct(AuthorizationManager $authorizationManager)
	{
		parent::__construct("System", new AuthorizedSystemContents(), array(
			"delete_user_system" => new RestrictedOperationPage("Unauthorize user to system", new AuthorizedSystemContents(), $authorizationManager)
		));
		$this->authorizationManager = $authorizationManager;
	}

	public function checkAccessibility(): bool
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
