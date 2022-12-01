<?php
namespace SBExampleApps\Homework\Model\Page\Content;
use SBLayout\Model\Page\Content\Contents;

class TestContents extends Contents
{
	public function __construct()
	{
		parent::__construct("tests/test.php", "tests/test.php");
	}
}
?>
