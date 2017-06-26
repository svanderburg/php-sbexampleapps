<?php
require_once("crud/model/page/StaticContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/ChangeLogEntriesCRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");

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
			new Contents("crud/changelog.inc.php"),
			/* Error contents */
			new Contents("crud/error.inc.php"),

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
