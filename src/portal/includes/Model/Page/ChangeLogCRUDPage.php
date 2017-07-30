<?php
namespace SBExampleApps\Portal\Model\Page;
use PDO;
use SBLayout\Model\Page\Page;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\Page\StaticContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Portal\Model\CRUD\ChangeLogEntriesCRUDModel;

class ChangeLogCRUDPage extends StaticContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = null)
	{
		$baseURL = Page::computeBaseURL();

		parent::__construct("Changelog",
			/* Key fields */
			array(),
			/* Default contents */
			new Contents("crud/changelog.php"),
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
		return new ChangeLogEntriesCRUDModel($this, $this->dbh, $this->authorizationManager);
	}
}
?>
