<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBData\Model\Value\Value;
use SBData\Model\Value\NaturalNumberValue;
use SBLayout\Model\Page\ContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\Page\CRUDMasterPage;
use SBCrud\Model\Page\OperationPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\Page\Content\AuthorContents;

class AuthorsCRUDPage extends CRUDMasterPage
{
	public PDO $dbh;

	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct("Authors", "authorId", new Contents("authors.php", "authors.php"), array(
			"create_author" => new OperationPage("Create author", new AuthorContents()),
			"insert_author" => new OperationPage("Insert author", new AuthorContents())
		));

		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	public function createParamValue(): Value
	{
		return new NaturalNumberValue(true);
	}

	public function createDetailPage(array $query): ?ContentPage
	{
		return new AuthorCRUDPage($this->dbh, $query["authorId"]);
	}
}
?>
