<?php
namespace SBExampleApps\Users\Model\Page;
use SBCrud\Model\Page\CRUDDetailPage;
use SBExampleApps\Users\Model\Page\Content\AuthorizedSystemContents;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Auth\Model\Page\RestrictedOperationPage;

class AuthorizedSystemsCRUDPage extends CRUDDetailPage
{
	public AuthorizationManager $authorizationManager;

	public function __construct(AuthorizationManager $authorizationManager)
	{
		parent::__construct("Authorized systems", new AuthorizedSystemContents(), array(
			"insert_user_system" => new RestrictedOperationPage("Authorize user to system", new AuthorizedSystemContents(), $authorizationManager),
			"delete_user_system" => new RestrictedOperationPage("Unauthorized user to system", new AuthorizedSystemContents(), $authorizationManager)
		));
		$this->authorizationManager = $authorizationManager;
	}
}
?>
