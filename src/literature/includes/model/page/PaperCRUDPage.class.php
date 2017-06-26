<?php
require_once("crud/model/page/StaticContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/PaperCRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");
require_once("data/model/field/NumericIntTextField.class.php");

class PaperCRUDPage extends StaticContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = null)
	{
		parent::__construct("Paper",
			/* Key fields */
			array(
				"conferenceId" => new NumericIntTextField(true),
				"paperId" => new NumericIntTextField(true)
			),
			/* Default contents */
			new Contents("crud/paper.inc.php"),
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
		return new PaperCRUDModel($this, $this->dbh, $this->authorizationManager);
	}
}
?>
