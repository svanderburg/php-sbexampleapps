<?php
require_once("crud/model/page/StaticContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/NewsMessageCRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");

class NewsMessageCRUDPage extends StaticContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = null)
	{
		$baseURL = Page::computeBaseURL();
		$htmlEditorJsPath = $baseURL."/lib/sbeditor/editor/scripts/htmleditor.js";

		parent::__construct("News message",
			/* Key fields */
			array(
				"messageId" => new NumericIntTextField("Id", true)
			),
			/* Default contents */
			new Contents("crud/newsmessage.inc.php", null, null, array($htmlEditorJsPath)),
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
		return new NewsMessageCRUDModel($this, $this->dbh, $this->authorizationManager);
	}
}
?>
