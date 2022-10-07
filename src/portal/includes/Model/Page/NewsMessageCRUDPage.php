<?php
namespace SBExampleApps\Portal\Model\Page;
use PDO;
use SBLayout\Model\Page\Page;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Value\IntegerValue;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\Page\StaticContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Portal\Model\CRUD\NewsMessageCRUDModel;

class NewsMessageCRUDPage extends StaticContentCRUDPage
{
	public PDO $dbh;

	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = array())
	{
		$baseURL = Page::computeBaseURL();
		$htmlEditorJsPath = $baseURL."/scripts/htmleditor.js";

		parent::__construct("News message",
			/* Key values */
			array(
				"messageId" => new IntegerValue(true)
			),
			/* Default contents */
			new Contents("crud/newsmessage.php", null, null, array($htmlEditorJsPath)),
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
		return new NewsMessageCRUDModel($this, $this->dbh, $this->authorizationManager);
	}
}
?>
