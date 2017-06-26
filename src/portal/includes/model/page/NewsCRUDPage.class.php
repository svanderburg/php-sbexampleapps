<?php
require_once("crud/model/page/DynamicContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/NewsCRUDModel.class.php");
require_once(dirname(__FILE__)."/../crud/NewsMessageCRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");

class NewsCRUDPage extends DynamicContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, $dynamicSubPage = null)
	{
		$baseURL = Page::computeBaseURL();
		$htmlEditorJsPath = $baseURL."/lib/sbeditor/editor/scripts/htmleditor.js";

		parent::__construct("News",
			/* Parameter name */
			"messageId",
			/* Key fields */
			array(),
			/* Default contents */
			new Contents("crud/news.inc.php"),
			/* Error contents */
			new Contents("crud/error.inc.php"),
			/* Contents per operation */
			array(
				"create_newsmessage" => new Contents("crud/newsmessage.inc.php", null, null, array($htmlEditorJsPath)),
				"insert_newsmessage" => new Contents("crud/newsmessage.inc.php", null, null, array($htmlEditorJsPath))
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
				case "create_newsmessage":
				case "insert_newsmessage":
					return new NewsMessageCRUDModel($this, $this->dbh, $this->authorizationManager);
				default:
					return new NewsCRUDModel($this, $this->dbh);
			}
		}
		else
			return new NewsCRUDModel($this, $this->dbh);
	}
}
?>
