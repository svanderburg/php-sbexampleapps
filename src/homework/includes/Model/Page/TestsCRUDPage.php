<?php
namespace SBExampleApps\Homework\Model\Page;
use PDO;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\Page\DynamicContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Homework\Model\CRUD\TestsCRUDModel;
use SBExampleApps\Homework\Model\CRUD\TestCRUDModel;

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
			new Contents("crud/tests.php"),
			/* Error contents */
			new Contents("crud/error.php"),
			/* Contents per operation */
			array(
				"create_test" => new Contents("crud/test.php"),
				"insert_test" => new Contents("crud/test.php")
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
