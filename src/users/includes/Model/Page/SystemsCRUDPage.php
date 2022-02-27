<?php
namespace SBExampleApps\Users\Model\Page;
use PDO;
use SBLayout\Model\Page\Page;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\Page\DynamicContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Users\Model\CRUD\SystemsCRUDModel;
use SBExampleApps\Users\Model\CRUD\SystemCRUDModel;

class SystemsCRUDPage extends DynamicContentCRUDPage
{
	public PDO $dbh;

	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, Page $dynamicSubPage = null)
	{
		parent::__construct("Systems",
			/* Parameter name */
			"systemId",
			/* Key fields */
			array(),
			/* Default contents */
			new Contents("crud/systems.php"),
			/* Error contents */
			new Contents("crud/error.php"),
			/* Contents per operation */
			array(
				"create_system" => new Contents("crud/system.php"),
				"insert_system" => new Contents("crud/system.php")
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
				case "create_system":
				case "insert_system":
					return new SystemCRUDModel($this, $this->dbh);
				default:
					return new SystemsCRUDModel($this, $this->dbh);
			}
		}
		else
			return new SystemsCRUDModel($this, $this->dbh);
	}

	public function checkAccessibility(): bool
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
