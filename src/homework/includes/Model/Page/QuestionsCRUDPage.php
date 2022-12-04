<?php
namespace SBExampleApps\Homework\Model\Page;
use PDO;
use SBLayout\Model\Page\ContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Value\Value;
use SBData\Model\Value\NaturalNumberValue;
use SBCrud\Model\Page\CRUDMasterPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Auth\Model\Page\RestrictedOperationPage;
use SBExampleApps\Homework\Model\Page\Content\QuestionContents;

class QuestionsCRUDPage extends CRUDMasterPage
{
	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct("Questions", "questionId", new Contents("tests/test/questions.php", "tests/test/questions.php"), array(
			"create_question" => new RestrictedOperationPage("Create question", new QuestionContents(), $authorizationManager),
			"insert_question" => new RestrictedOperationPage("Insert question", new QuestionContents(), $authorizationManager)
		));

		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	public function createParamValue(): Value
	{
		return new NaturalNumberValue(true);
	}

	public function createDetailPage(array $query): ?ContentPage
	{
		return new QuestionCRUDPage($this->dbh, $this->authorizationManager, $query["testId"], $query["questionId"]);
	}

	public function checkAccessibility(): bool
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
