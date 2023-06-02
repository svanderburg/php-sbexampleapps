<?php
namespace SBExampleApps\Portal\Model\Page;
use SBCrud\Model\Page\CRUDDetailPage;
use SBCrud\Model\Page\HiddenOperationPage;
use SBExampleApps\Portal\Model\Page\Content\ChangeLogEntryContents;

class ChangeLogEntryCRUDPage extends CRUDDetailPage
{
	public function __construct()
	{
		parent::__construct("Changelog entry", new ChangeLogEntryContents(), array(
			"remove_changelogentry" => new HiddenOperationPage("Remove changelog entry", new ChangeLogEntryContents())
		));
	}
}
?>
