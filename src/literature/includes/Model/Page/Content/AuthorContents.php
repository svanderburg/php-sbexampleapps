<?php
namespace SBExampleApps\Literature\Model\Page\Content;
use SBLayout\Model\Page\Content\Contents;

class AuthorContents extends Contents
{
	public function __construct()
	{
		parent::__construct("authors/author.php", "authors/author.php");
	}
}
?>
