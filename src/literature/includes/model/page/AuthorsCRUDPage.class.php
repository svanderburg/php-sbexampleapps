<?php
require_once("crud/model/page/DynamicContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/AuthorsCRUDModel.class.php");
require_once(dirname(__FILE__)."/../crud/AuthorCRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");

class AuthorsCRUDPage extends DynamicContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, $dynamicSubPage = null)
	{
		parent::__construct("Authors",
			/* Parameter name */
			"authorId",
			/* Key fields */
			array(),
			/* Default contents */
			new Contents("crud/authors.inc.php"),
			/* Error contents */
			new Contents("crud/error.inc.php"),
			/* Contents per operation */
			array(
				"create_author" => new Contents("crud/author.inc.php"),
				"insert_author" => new Contents("crud/author.inc.php")
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
				case "create_author":
				case "insert_author":
					return new AuthorCRUDModel($this, $this->dbh, $this->authorizationManager);
				default:
					return new AuthorsCRUDModel($this, $this->dbh);
			}
		}
		else
			return new AuthorsCRUDModel($this, $this->dbh);
	}
}
?>
