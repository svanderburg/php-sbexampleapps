<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\Page\DynamicContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\CRUD\PublishersCRUDModel;
use SBExampleApps\Literature\Model\CRUD\PublisherCRUDModel;

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
			new Contents("crud/publishers.php"),
			/* Error contents */
			new Contents("crud/error.php"),
			/* Contents per operation */
			array(
				"create_publisher" => new Contents("crud/publisher.php"),
				"insert_publisher" => new Contents("crud/publisher.php")
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
