<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\Page\ContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Value\Value;
use SBCrud\Model\Page\CRUDMasterPage;
use SBCrud\Model\Page\OperationPage;
use SBExampleApps\Literature\Model\Page\Content\PublisherContents;

class PublishersCRUDPage extends CRUDMasterPage
{
	public PDO $dbh;

	public function __construct(PDO $dbh)
	{
		parent::__construct("Publishers", "publisherId", new Contents("publishers.php", "publishers.php"), array(
			"create_publisher" => new OperationPage("Create publisher", new PublisherContents()),
			"insert_publisher" => new OperationPage("Insert publisher", new PublisherContents())
		));

		$this->dbh = $dbh;
	}

	public function createParamValue(): Value
	{
		return new Value(true, 255);
	}

	public function createDetailPage(array $query): ?ContentPage
	{
		return new PublisherCRUDPage($this->dbh, $query["publisherId"]);
	}
}
?>
