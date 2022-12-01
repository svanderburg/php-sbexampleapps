<?php
namespace SBExampleApps\Literature\Model\Page\Content;
use SBLayout\Model\Page\Content\Contents;

class PublisherContents extends Contents
{
	public function __construct()
	{
		parent::__construct("publishers/publisher.php", "publishers/publisher.php");
	}
}
?>
