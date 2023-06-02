<?php
namespace SBExampleApps\Homework\Model\Page;
use PDO;
use SBLayout\Model\PageNotFoundException;
use SBCrud\Model\Page\CRUDDetailPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Auth\Model\Page\RestrictedOperationPage;
use SBExampleApps\Auth\Model\Page\RestrictedHiddenOperationPage;
use SBExampleApps\Homework\Model\Entity\QuestionEntity;
use SBExampleApps\Homework\Model\Page\Content\QuestionContents;

class QuestionCRUDPage extends CRUDDetailPage
{
	public array $entity;

	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, string $testId, string $questionId)
	{
		parent::__construct("Question", new QuestionContents(), array(
			"update_question" => new RestrictedHiddenOperationPage("Update question", new QuestionContents(), $authorizationManager),
			"delete_question" => new RestrictedOperationPage("Delete question", new QuestionContents(), $authorizationManager),
		));

		$this->authorizationManager = $authorizationManager;

		$stmt = QuestionEntity::queryOne($dbh, $testId, $questionId);

		if(($entity = $stmt->fetch()) === false)
			throw new PageNotFoundException("Cannot find question with id: ".$questionId);

		$this->entity = $entity;
		// Do not change the title, because questions are too detailed/long
	}

	public function checkAccessibility(): bool
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
