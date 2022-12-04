<?php
namespace SBExampleApps\Literature\Model\Page\Content;
use SBLayout\Model\Page\Content\Contents;

class PaperAuthorsContents extends Contents
{
	public function __construct()
	{
		parent::__construct("conferences/conference/papers/paper/authors.php", "conferences/conference/papers/paper/authors.php");
	}
}
?>
