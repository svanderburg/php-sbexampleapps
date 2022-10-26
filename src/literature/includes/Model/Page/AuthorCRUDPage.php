<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\ParameterMap;
use SBData\Model\Value\IntegerValue;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\Page\StaticContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\CRUD\AuthorCRUDModel;

class AuthorCRUDPage extends StaticContentCRUDPage
{
	public PDO $dbh;

	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = array())
	{
		parent::__construct("Author",
			/* Key parameters */
			new ParameterMap(array(
				"authorId" => new IntegerValue(true)
			)),
			/* Request parameters */
			new ParameterMap(),
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

	public function constructCRUDModel(): CRUDModel
	{
		return new AuthorCRUDModel($this, $this->dbh, $this->authorizationManager);
	}
}
?>
