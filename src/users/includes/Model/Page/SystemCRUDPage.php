<?php
namespace SBExampleApps\Users\Model\Page;
use PDO;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Field\TextField;
use SBCrud\Model\Page\StaticContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Users\Model\CRUD\SystemCRUDModel;

class SystemCRUDPage extends StaticContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = null)
	{
		parent::__construct("System",
			/* Key fields */
			array(
				"systemId" => new TextField("Id", true, 20, 255)
			),
			/* Default contents */
			new Contents("crud/system.php"),
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
		return new SystemCRUDModel($this, $this->dbh);
	}

	public function checkAccessibility()
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
