<?php
require_once("crud/model/page/DynamicContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/TestsCRUDModel.class.php");
require_once(dirname(__FILE__)."/../crud/TestCRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");

class TestsCRUDPage extends DynamicContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, $dynamicSubPage = null)
	{
		parent::__construct("Tests",
			/* Parameter name */
			"testId",
			/* Key fields */
			array(),
			/* Default contents */
			new Contents("crud/tests.inc.php"),
			/* Error contents */
			new Contents("crud/error.inc.php"),
			/* Contents per operation */
			array(
				"create_test" => new Contents("crud/test.inc.php"),
				"insert_test" => new Contents("crud/test.inc.php")
			),
			$dynamicSubPage);

		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}
	
	public function constructCRUDModel()
	{
		if(array_key_exists("__operation", $_REQUEST))
		{
			switch($_REQUEST["__operation"])
			{
				case "create_test":
				case "insert_test":
					return new TestCRUDModel($this, $this->dbh);
				default:
					return new TestsCRUDModel($this, $this->dbh);
			}
		}
		else
			return new TestsCRUDModel($this, $this->dbh);
	}

	public function checkAccessibility()
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
