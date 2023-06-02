<?php
namespace SBExampleApps\Portal\Model\Page;
use SBLayout\Model\Page\ContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Value\Value;
use SBCrud\Model\Page\CRUDMasterPage;
use SBCrud\Model\Page\OperationPage;
use SBCrud\Model\Page\HiddenOperationPage;
use SBExampleApps\Portal\Model\Page\Content\ChangeLogEntryContents;

class ChangeLogCRUDPage extends CRUDMasterPage
{
	public function __construct()
	{
		parent::__construct("Changelog", "logId", new Contents("changelog.php", "changelog.php"), array(
			"create_changelogentry" => new OperationPage("Create changelog entry", new ChangeLogEntryContents()),
			"insert_changelogentry" => new HiddenOperationPage("Insert changelog entry", new ChangeLogEntryContents()),
			"update_changelogentry" => new HiddenOperationPage("Update changelog entry", new ChangeLogEntryContents())
		));
	}

	public function createParamValue(): Value
	{
		return new Value(true, 255);
	}

	public function createDetailPage(array $query): ?ContentPage
	{
		return new ChangeLogEntryCRUDPage();
	}
}
?>
