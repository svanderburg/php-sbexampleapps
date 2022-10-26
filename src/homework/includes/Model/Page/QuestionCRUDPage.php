<?php
namespace SBExampleApps\Homework\Model\Page;
use PDO;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\ParameterMap;
use SBData\Model\Value\Value;
use SBData\Model\Value\IntegerValue;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\Page\StaticContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Homework\Model\CRUD\QuestionCRUDModel;

class QuestionCRUDPage extends StaticContentCRUDPage
{
	public PDO $dbh;

	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = array())
	{
		parent::__construct("Question",
			/* Key parameters */
			new ParameterMap(array(
				"testId" => new Value(true, 255),
				"questionId" => new IntegerValue(true)
			)),
			/* Request parameters */
			new ParameterMap(),
			/* Default contents */
			new Contents("crud/question.php"),
			/* Error contents */
			new Contents("crud/error.php"),
			/* Contents per operation */
			array(),
			$subPages);

		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	public function constructCRUDModel(): CRUDModel
	{
		return new QuestionCRUDModel($this, $this->dbh);
	}

	public function checkAccessibility(): bool
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
