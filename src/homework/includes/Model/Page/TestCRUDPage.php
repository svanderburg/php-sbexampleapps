<?php
namespace SBExampleApps\Homework\Model\Page;
use PDO;
use SBLayout\Model\PageNotFoundException;
use SBLayout\Model\Page\PageAlias;
use SBCrud\Model\Page\CRUDDetailPage;
use SBExampleApps\Auth\Model\Page\RestrictedOperationPage;
use SBExampleApps\Auth\Model\Page\RestrictedHiddenOperationPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Homework\Model\Page\Content\TestContents;
use SBExampleApps\Homework\Model\Entity\TestEntity;

class TestCRUDPage extends CRUDDetailPage
{
	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, string $testId)
	{
		parent::__construct("Test", new TestContents(), array(
			"update_test" => new RestrictedHiddenOperationPage("Update test", new TestContents(), $authorizationManager),
			"delete_test" => new RestrictedOperationPage("Delete test", new TestContents(), $authorizationManager)
		), array(
			"test" => new PageAlias("Test", "tests/".$testId),
			"questions" => new QuestionsCRUDPage($dbh, $authorizationManager)
		));

		$this->authorizationManager = $authorizationManager;

		$stmt = TestEntity::queryOne($dbh, $testId);

		if(($entity = $stmt->fetch()) === false)
			throw new PageNotFoundException("Cannot find test with id: ".$testId);

		$this->title = $entity["Title"];
		$this->entity = $entity;
	}

	public function checkAccessibility(): bool
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
