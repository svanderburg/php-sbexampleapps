<?php
require_once("crud/model/page/DynamicContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/SystemsCRUDModel.class.php");
require_once(dirname(__FILE__)."/../crud/SystemCRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");

class SystemsCRUDPage extends DynamicContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, $dynamicSubPage = null)
	{
		parent::__construct("Systems",
			/* Parameter name */
			"systemId",
			/* Key fields */
			array(),
			/* Default contents */
			new Contents("crud/systems.inc.php"),
			/* Error contents */
			new Contents("crud/error.inc.php"),
			/* Contents per operation */
			array(
				"create_system" => new Contents("crud/system.inc.php"),
				"insert_system" => new Contents("crud/system.inc.php")
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

	public function checkAccessibility()
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
