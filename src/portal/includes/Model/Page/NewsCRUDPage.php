<?php
namespace SBExampleApps\Portal\Model\Page;
use PDO;
use SBLayout\Model\Page\ContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\ParameterMap;
use SBData\Model\Value\Value;
use SBData\Model\Value\NaturalNumberValue;
use SBData\Model\Value\PageValue;
use SBCrud\Model\Page\CRUDMasterPage;
use SBCrud\Model\Page\OperationPage;
use SBExampleApps\Portal\Model\Page\Content\NewsMessageContents;

class NewsCRUDPage extends CRUDMasterPage
{
	public function __construct(PDO $dbh)
	{
		parent::__construct("News", "messageId", new Contents("news.php", "news.php"), array(
			"create_newsmessage" => new OperationPage("Create news message", new NewsMessageContents()),
			"insert_newsmessage" => new OperationPage("Insert news message", new NewsMessageContents())
		));

		$this->dbh = $dbh;
	}

	public function createParamValue(): Value
	{
		return new NaturalNumberValue(true);
	}

	public function createRequestParameterMap(): ParameterMap
	{
		return new ParameterMap(array(
			"page" => new PageValue()
		));
	}

	public function createDetailPage(array $query): ?ContentPage
	{
		return new NewsMessageCRUDPage($this->dbh, $query["messageId"]);
	}
}
?>
