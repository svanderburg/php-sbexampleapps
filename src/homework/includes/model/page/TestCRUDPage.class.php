<?php
require_once("crud/model/page/StaticContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/TestCRUDModel.class.php");
require_once(dirname(__FILE__)."/../crud/QuestionCRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");

class TestCRUDPage extends StaticContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = null)
	{
		parent::__construct("Test",
			/* Key fields */
			array(
				"testId" => new TextField("Id", true, 20, 255)
			),
			/* Default contents */
			new Contents("crud/test.inc.php"),
			/* Error contents */
			new Contents("crud/error.inc.php"),

			/* Contents per operation */
			array(
				"create_question" => new Contents("crud/question.inc.php"),
				"insert_question" => new Contents("crud/question.inc.php")
			),
			$subPages);

		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	public function constructCRUDModel()
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

	public function checkAccessibility()
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
