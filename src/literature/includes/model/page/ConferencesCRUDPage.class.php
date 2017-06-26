<?php
require_once("crud/model/page/DynamicContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/ConferencesCRUDModel.class.php");
require_once(dirname(__FILE__)."/../crud/ConferenceCRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");

class ConferencesCRUDPage extends DynamicContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, $dynamicSubPage = null)
	{
		parent::__construct("Conferences",
			/* Parameter name */
			"conferenceId",
			/* Key fields */
			array(),
			/* Default contents */
			new Contents("crud/conferences.inc.php"),
			/* Error contents */
			new Contents("crud/error.inc.php"),
			/* Contents per operation */
			array(
				"create_conference" => new Contents("crud/conference.inc.php"),
				"insert_conference" => new Contents("crud/conference.inc.php")
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
				case "create_conference":
				case "insert_conference":
					return new ConferenceCRUDModel($this, $this->dbh, $this->authorizationManager);
				default:
					return new ConferencesCRUDModel($this, $this->dbh);
			}
		}
		else
			return new ConferencesCRUDModel($this, $this->dbh);
	}
}
?>
