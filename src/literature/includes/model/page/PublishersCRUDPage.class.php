<?php
require_once("crud/model/page/DynamicContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/PublishersCRUDModel.class.php");
require_once(dirname(__FILE__)."/../crud/PublisherCRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");

class PublishersCRUDPage extends DynamicContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, $dynamicSubPage = null)
	{
		parent::__construct("Publishers",
			/* Parameter name */
			"publisherId",
			/* Key fields */
			array(),
			/* Default contents */
			new Contents("crud/publishers.inc.php"),
			/* Error contents */
			new Contents("crud/error.inc.php"),
			/* Contents per operation */
			array(
				"create_publisher" => new Contents("crud/publisher.inc.php"),
				"insert_publisher" => new Contents("crud/publisher.inc.php")
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
				case "create_publisher":
				case "insert_publisher":
					return new PublisherCRUDModel($this, $this->dbh, $this->authorizationManager);
				default:
					return new PublishersCRUDModel($this, $this->dbh);
			}
		}
		else
			return new PublishersCRUDModel($this, $this->dbh);
	}
}
?>
