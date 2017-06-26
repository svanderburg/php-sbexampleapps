<?php
require_once("crud/model/page/DynamicContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/UsersCRUDModel.class.php");
require_once(dirname(__FILE__)."/../crud/UserCRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");

class UsersCRUDPage extends DynamicContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, $dynamicSubPage = null)
	{
		parent::__construct("Users",
			/* Parameter name */
			"Username",
			/* Key fields */
			array(),
			/* Default contents */
			new Contents("crud/users.inc.php"),
			/* Error contents */
			new Contents("crud/error.inc.php"),
			/* Contents per operation */
			array(
				"create_user" => new Contents("crud/user.inc.php"),
				"insert_user" => new Contents("crud/user.inc.php")
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
				case "create_user":
				case "insert_user":
					return new UserCRUDModel($this, $this->dbh);
				default:
					return new UsersCRUDModel($this, $this->dbh);
			}
		}
		else
			return new UsersCRUDModel($this, $this->dbh);
	}

	public function checkAccessibility()
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
