<?php
namespace SBExampleApps\Homework\Model\Page;
use PDO;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Value\Value;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\Page\StaticContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Homework\Model\CRUD\QuestionCRUDModel;
use SBExampleApps\Homework\Model\CRUD\TestCRUDModel;

class TestCRUDPage extends StaticContentCRUDPage
{
	public PDO $dbh;

	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = array())
	{
		parent::__construct("Test",
			/* Key values */
			array(
				"testId" => new Value(true, 255)
			),
			/* Default contents */
			new Contents("crud/test.php"),
			/* Error contents */
			new Contents("crud/error.php"),

			/* Contents per operation */
			array(
				"create_question" => new Contents("crud/question.php"),
				"insert_question" => new Contents("crud/question.php")
			),
			$subPages);

		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	public function constructCRUDModel(): CRUDModel
	{
		if(array_key_exists("__operation", $_REQUEST))
		{
			switch($_REQUEST["__operation"])
			{
				case "create_question":
				case "insert_question":
					return new QuestionCRUDModel($this, $this->dbh);
				default:
					return new TestCRUDModel($this, $this->dbh);
			}
		}
		else
			return new TestCRUDModel($this, $this->dbh);
	}

	public function checkAccessibility(): bool
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
