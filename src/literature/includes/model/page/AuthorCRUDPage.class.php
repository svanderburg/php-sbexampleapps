<?php
require_once("crud/model/page/StaticContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/AuthorCRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");

class AuthorCRUDPage extends StaticContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = null)
	{
		parent::__construct("Author",
			/* Key fields */
			array(
				"authorId" => new NumericIntTextField("Id", true)
			),
			/* Default contents */
			new Contents("crud/author.inc.php"),
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
		return new AuthorCRUDModel($this, $this->dbh, $this->authorizationManager);
	}
}
?>
