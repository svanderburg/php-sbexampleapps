<?php
namespace SBExampleApps\Literature\Model\Page\Content;
use SBLayout\Model\Page\Content\Contents;

class ConferenceEditorContents extends Contents
{
	public function __construct()
	{
		parent::__construct("conferences/conference/editor/editor.php", "conferences/conference/editors/editor.php");
	}
}
?>
