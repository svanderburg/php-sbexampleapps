<?php
namespace SBExampleApps\Portal\Model\Page;
use PDO;
use SBLayout\Model\Page\Page;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\Page\DynamicContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Portal\Model\CRUD\NewsCRUDModel;
use SBExampleApps\Portal\Model\CRUD\NewsMessageCRUDModel;

class NewsCRUDPage extends DynamicContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, $dynamicSubPage = null)
	{
		$baseURL = Page::computeBaseURL();
		$htmlEditorJsPath = $baseURL."/scripts/htmleditor.js";

		parent::__construct("News",
			/* Parameter name */
			"messageId",
			/* Key fields */
			array(),
			/* Default contents */
			new Contents("crud/news.php"),
			/* Error contents */
			new Contents("crud/error.php"),
			/* Contents per operation */
			array(
				"create_newsmessage" => new Contents("crud/newsmessage.php", null, null, array($htmlEditorJsPath)),
				"insert_newsmessage" => new Contents("crud/newsmessage.php", null, null, array($htmlEditorJsPath))
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
