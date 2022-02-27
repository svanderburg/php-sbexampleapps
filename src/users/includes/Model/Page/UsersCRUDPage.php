<?php
namespace SBExampleApps\Users\Model\Page;
use PDO;
use SBLayout\Model\Page\Page;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\Page\DynamicContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Users\Model\CRUD\UsersCRUDModel;
use SBExampleApps\Users\Model\CRUD\UserCRUDModel;

class UsersCRUDPage extends DynamicContentCRUDPage
{
	public PDO $dbh;

	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, Page $dynamicSubPage = null)
	{
		parent::__construct("Users",
			/* Parameter name */
			"Username",
			/* Key fields */
			array(),
			/* Default contents */
			new Contents("crud/users.php"),
			/* Error contents */
			new Contents("crud/error.php"),
			/* Contents per operation */
			array(
				"create_user" => new Contents("crud/user.php"),
				"insert_user" => new Contents("crud/user.php")
			),
			$dynamicSubPage);

		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}
	
	public function constructCRUDModel(): CRUDModel
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

	public function checkAccessibility(): bool
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
