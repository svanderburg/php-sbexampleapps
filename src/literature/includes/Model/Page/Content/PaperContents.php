<?php
namespace SBExampleApps\Literature\Model\Page\Content;
use SBLayout\Model\Page\Content\Contents;

class PaperContents extends Contents
{
	public function __construct()
	{
		parent::__construct("conferences/conference/papers/paper.php", "conferences/conference/papers/paper.php");
	}
}
?>
