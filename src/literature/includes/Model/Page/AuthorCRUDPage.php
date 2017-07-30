<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Field\NumericIntTextField;
use SBCrud\Model\Page\StaticContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\CRUD\AuthorCRUDModel;

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
			new Contents("crud/author.php"),
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
		return new AuthorCRUDModel($this, $this->dbh, $this->authorizationManager);
	}
}
?>
