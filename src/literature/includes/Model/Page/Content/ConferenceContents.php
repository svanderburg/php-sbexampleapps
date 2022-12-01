<?php
namespace SBExampleApps\Literature\Model\Page\Content;
use SBLayout\Model\Page\Content\Contents;

class ConferenceContents extends Contents
{
	public function __construct()
	{
		parent::__construct("conferences/conference.php", "conferences/conference.php");
	}
}
?>
