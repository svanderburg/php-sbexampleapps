<?php
namespace SBExampleApps\Users\Model\Page;
use PDO;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Field\TextField;
use SBCrud\Model\Page\StaticContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Users\Model\CRUD\UserCRUDModel;

class UserCRUDPage extends StaticContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = null)
	{
		parent::__construct("User",
			/* Key fields */
			array(
				"Username" => new TextField("Username", true, 20, 255)
			),
			/* Default contents */
			new Contents("crud/user.php"),
			/* Error contents */
			new Contents("crud/error.php"),

			/* Contents per operation */
			array(),
			$subPages);

		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	public function constructCRUDModel()
	{
		return new UserCRUDModel($this, $this->dbh);
	}

	public function checkAccessibility()
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
