<?php
namespace SBExampleApps\Homework\Model\Page;
use PDO;
use SBLayout\Model\Page\ContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Value\Value;
use SBCrud\Model\Page\CRUDMasterPage;
use SBExampleApps\Auth\Model\Page\RestrictedOperationPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Homework\Model\Page\Content\TestContents;

class TestsCRUDPage extends CRUDMasterPage
{
	public PDO $dbh;

	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct("Tests", "testId", new Contents("tests.php", "tests.php"), array(
			"create_test" => new RestrictedOperationPage("Create test", new TestContents(), $authorizationManager),
			"insert_test" => new RestrictedOperationPage("Insert test", new TestContents(), $authorizationManager)
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
		return new TestCRUDPage($this->dbh, $this->authorizationManager, $query["testId"]);
	}

	public function checkAccessibility(): bool
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
