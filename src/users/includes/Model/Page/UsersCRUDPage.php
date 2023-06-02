<?php
namespace SBExampleApps\Users\Model\Page;
use PDO;
use SBLayout\Model\Page\ContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Value\Value;
use SBCrud\Model\Page\CRUDMasterPage;
use SBExampleApps\Users\Model\Page\Content\UserContents;
use SBExampleApps\Auth\Model\Page\RestrictedOperationPage;
use SBExampleApps\Auth\Model\Page\RestrictedHiddenOperationPage;
use SBExampleApps\Auth\Model\AuthorizationManager;

class UsersCRUDPage extends CRUDMasterPage
{
	public PDO $dbh;

	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct("Users", "Username", new Contents("users.php", "users.php"), array(
			"create_user" => new RestrictedOperationPage("Create user", new UserContents(), $authorizationManager),
			"insert_user" => new RestrictedHiddenOperationPage("Insert user", new UserContents(), $authorizationManager)
		));

		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	public function createParamValue(): Value
	{
		return new Value(true, 255);
	}

	public function createDetailPage(array $query): ?ContentPage
	{
		return new UserCRUDPage($this->dbh, $this->authorizationManager, $query["Username"]);
	}

	public function checkAccessibility(): bool
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
