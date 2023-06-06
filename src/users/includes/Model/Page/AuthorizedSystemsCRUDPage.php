<?php
namespace SBExampleApps\Users\Model\Page;
use SBLayout\Model\Page\ContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Value\Value;
use SBCrud\Model\Page\CRUDMasterPage;
use SBExampleApps\Users\Model\Page\Content\AuthorizedSystemContents;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Auth\Model\Page\RestrictedOperationPage;

class AuthorizedSystemsCRUDPage extends CRUDMasterPage
{
	public AuthorizationManager $authorizationManager;

	public function __construct(AuthorizationManager $authorizationManager)
	{
		parent::__construct("Authorized systems", "systemId", new Contents("users/user/authorizedsystems.php", "users/user/authorizedsystems.php"), array(
			"insert_user_system" => new RestrictedOperationPage("Authorize user to system", new AuthorizedSystemContents(), $authorizationManager)
		));
		$this->authorizationManager = $authorizationManager;
	}

	public function createParamValue(): Value
	{
		return new Value(true, 255);
	}

	public function createDetailPage(array $query): ?ContentPage
	{
		return new AuthorizedSystemCRUDPage($this->authorizationManager);
	}

	public function checkAccessibility(): bool
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
