<?php
require_once("crud/model/page/StaticContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/SearchCRUDModel.class.php");

class SearchCRUDPage extends StaticContentCRUDPage
{
	public $dbh;

	public function __construct(PDO $dbh, array $subPages = null)
	{
		parent::__construct("Search papers",
			/* Key fields */
			array(),
			/* Default contents */
			new Contents("crud/search.inc.php"),
			/* Error contents */
			new Contents("crud/error.inc.php"),

			/* Contents per operation */
			array(),
			$subPages);

		$this->dbh = $dbh;
	}

	public function constructCRUDModel()
	{
		return new SearchCRUDModel($this, $this->dbh);
	}
}
?>
