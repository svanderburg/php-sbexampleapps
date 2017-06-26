<?php
require_once("crud/model/page/StaticContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/PublisherCRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");

class PublisherCRUDPage extends StaticContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = null)
	{
		parent::__construct("Publisher",
			/* Key fields */
			array(
				"publisherId" => new TextField("Id", true, 20, 255)
			),
			/* Default contents */
			new Contents("crud/publisher.inc.php"),
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
		return new PublisherCRUDModel($this, $this->dbh, $this->authorizationManager);
	}
}
?>
