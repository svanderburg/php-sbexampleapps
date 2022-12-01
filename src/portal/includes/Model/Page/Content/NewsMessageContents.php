<?php
namespace SBExampleApps\Portal\Model\Page\Content;
use SBLayout\Model\Page\Page;
use SBLayout\Model\Page\Content\Contents;

class NewsMessageContents extends Contents
{
	public function __construct()
	{
		$baseURL = Page::computeBaseURL();
		$htmlEditorJsPath = $baseURL."/scripts/htmleditor.js";

		parent::__construct("news/newsmessage.php", "news/newsmessage.php", array(), array($htmlEditorJsPath));
	}
}
?>
