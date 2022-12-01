<?php
namespace SBExampleApps\Users\Model\Page;
use PDO;
use SBLayout\Model\Page\ContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Value\Value;
use SBCrud\Model\Page\CRUDMasterPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Auth\Model\Page\RestrictedOperationPage;
use SBExampleApps\Users\Model\Page\Content\SystemContents;

class SystemsCRUDPage extends CRUDMasterPage
{
	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct("Systems", "systemId", new Contents("systems.php", "systems.php"), array(
			"create_system" => new RestrictedOperationPage("Create system", new SystemContents(), $authorizationManager),
			"insert_system" => new RestrictedOperationPage("Insert system", new SystemContents(), $authorizationManager)
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
		return new SystemCRUDPage($this->dbh, $this->authorizationManager, $query["systemId"]);
	}

	public function checkAccessibility(): bool
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
