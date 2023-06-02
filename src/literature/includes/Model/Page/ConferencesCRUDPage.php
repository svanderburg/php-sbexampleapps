<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\Page\ContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Value\Value;
use SBData\Model\Value\NaturalNumberValue;
use SBCrud\Model\Page\CRUDMasterPage;
use SBCrud\Model\Page\OperationPage;
use SBCrud\Model\Page\HiddenOperationPage;
use SBExampleApps\Literature\Model\Page\Content\ConferenceContents;

class ConferencesCRUDPage extends CRUDMasterPage
{
	public PDO $dbh;

	public function __construct(PDO $dbh)
	{
		parent::__construct("Conferences", "conferenceId", new Contents("conferences.php", "conferences.php"), array(
			"create_conference" => new OperationPage("Create conference", new ConferenceContents()),
			"insert_conference" => new HiddenOperationPage("Delete conference", new ConferenceContents())
		));

		$this->dbh = $dbh;
	}

	public function createParamValue(): Value
	{
		return new NaturalNumberValue(true);
	}

	public function createDetailPage(array $query): ?ContentPage
	{
		return new ConferenceCRUDPage($this->dbh, $query["conferenceId"]);
	}
}
?>
