<?php
require_once("crud/model/page/StaticContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/ConferenceCRUDModel.class.php");
require_once(dirname(__FILE__)."/../crud/PaperCRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");

class ConferenceCRUDPage extends StaticContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = null)
	{
		parent::__construct("Conference",
			/* Key fields */
			array(
				"conferenceId" => new TextField("Id", true, 20, 255)
			),
			/* Default contents */
			new Contents("crud/conference.inc.php"),
			/* Error contents */
			new Contents("crud/error.inc.php"),

			/* Contents per operation */
			array(
				"create_paper" => new Contents("crud/paper.inc.php"),
				"insert_paper" => new Contents("crud/paper.inc.php")
			),
			$subPages);

		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	public function constructCRUDModel()
	{
		if(array_key_exists("__operation", $_REQUEST))
		{
			switch($_REQUEST["__operation"])
			{
				case "create_paper":
				case "insert_paper":
					return new PaperCRUDModel($this, $this->dbh, $this->authorizationManager);
				default:
					return new ConferenceCRUDModel($this, $this->dbh, $this->authorizationManager);
			}
		}
		else
			return new ConferenceCRUDModel($this, $this->dbh, $this->authorizationManager);
	}
}
?>
