<?php
namespace SBExampleApps\Portal\Model\Page\Content;
use SBLayout\Model\Page\Content\Contents;

class ChangeLogEntryContents extends Contents
{
	public function __construct()
	{
		parent::__construct("changelog/changelogentry.php", "changelog/changelogentry.php");
	}
}
?>
