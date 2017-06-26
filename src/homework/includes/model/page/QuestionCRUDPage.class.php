<?php
require_once("crud/model/page/StaticContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/QuestionCRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");
require_once("data/model/field/NumericIntTextField.class.php");

class QuestionCRUDPage extends StaticContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = null)
	{
		parent::__construct("Question",
			/* Key fields */
			array(
				"testId" => new TextField(true, 20, 255),
				"questionId" => new NumericIntTextField(true)
			),
			/* Default contents */
			new Contents("crud/question.inc.php"),
			/* Error contents */
			new Contents("crud/error.inc.php"),
			/* Contents per operation */
			array(),
			$subPages);

		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	public function constructCRUDModel()
	{
		return new QuestionCRUDModel($this, $this->dbh);
	}

	public function checkAccessibility()
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
