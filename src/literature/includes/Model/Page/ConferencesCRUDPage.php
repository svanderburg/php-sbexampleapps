<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\Page\Page;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\Page\DynamicContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\CRUD\ConferencesCRUDModel;
use SBExampleApps\Literature\Model\CRUD\ConferenceCRUDModel;

class ConferencesCRUDPage extends DynamicContentCRUDPage
{
	public PDO $dbh;

	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, Page $dynamicSubPage)
	{
		parent::__construct("Conferences",
			/* Parameter name */
			"conferenceId",
			/* Key values */
			array(),
			/* Default contents */
			new Contents("crud/conferences.php"),
			/* Error contents */
			new Contents("crud/error.php"),
			/* Contents per operation */
			array(
				"create_conference" => new Contents("crud/conference.php"),
				"insert_conference" => new Contents("crud/conference.php")
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
