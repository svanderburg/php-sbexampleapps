<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Field\TextField;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\Page\StaticContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\CRUD\ConferenceCRUDModel;
use SBExampleApps\Literature\Model\CRUD\PaperCRUDModel;

class ConferenceCRUDPage extends StaticContentCRUDPage
{
	public PDO $dbh;

	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = null)
	{
		parent::__construct("Conference",
			/* Key fields */
			array(
				"conferenceId" => new TextField("Id", true, 20, 255)
			),
			/* Default contents */
			new Contents("crud/conference.php"),
			/* Error contents */
			new Contents("crud/error.php"),

			/* Contents per operation */
			array(
				"create_paper" => new Contents("crud/paper.php"),
				"insert_paper" => new Contents("crud/paper.php")
			),
			$subPages);

		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	public function constructCRUDModel(): CRUDModel
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
