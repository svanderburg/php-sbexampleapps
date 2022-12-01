<?php
namespace SBExampleApps\Users\Model\Page\Content;
use SBLayout\Model\Page\Content\Contents;

class SystemContents extends Contents
{
	public function __construct()
	{
		parent::__construct("systems/system.php", "systems/system.php");
	}
}
?>
