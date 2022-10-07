<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\Page\StaticContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\CRUD\SearchCRUDModel;

class SearchCRUDPage extends StaticContentCRUDPage
{
	public PDO $dbh;

	public function __construct(PDO $dbh, array $subPages = array())
	{
		parent::__construct("Search papers",
			/* Key values */
			array(),
			/* Default contents */
			new Contents("crud/search.php"),
			/* Error contents */
			new Contents("crud/error.php"),
			/* Contents per operation */
			array(),
			$subPages);

		$this->dbh = $dbh;
	}

	public function constructCRUDModel(): CRUDModel
	{
		return new SearchCRUDModel($this, $this->dbh);
	}
}
?>
