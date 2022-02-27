<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\Page\Page;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\Page\DynamicContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\CRUD\AuthorsCRUDModel;
use SBExampleApps\Literature\Model\CRUD\AuthorCRUDModel;

class AuthorsCRUDPage extends DynamicContentCRUDPage
{
	public PDO $dbh;

	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, Page $dynamicSubPage = null)
	{
		parent::__construct("Authors",
			/* Parameter name */
			"authorId",
			/* Key fields */
			array(),
			/* Default contents */
			new Contents("crud/authors.php"),
			/* Error contents */
			new Contents("crud/error.php"),
			/* Contents per operation */
			array(
				"create_author" => new Contents("crud/author.php"),
				"insert_author" => new Contents("crud/author.php")
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
