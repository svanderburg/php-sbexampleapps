<?php
namespace SBExampleApps\Literature\Model\Page;
use SBLayout\Model\Page\ContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Value\Value;
use SBData\Model\Value\NaturalNumberValue;
use SBCrud\Model\Page\CRUDMasterPage;
use SBCrud\Model\Page\HiddenOperationPage;
use SBExampleApps\Literature\Model\Page\Content\ConferenceEditorContents;

class ConferenceEditorsCRUDPage extends CRUDMasterPage
{
	public function __construct()
	{
		parent::__construct("Editors", "authorId", new Contents("conferences/conference/editors.php", "conferences/conference/editors.php"), array(
			"insert_conference_author" => new HiddenOperationPage("Add editor", new ConferenceEditorContents())
		));
	}

	public function createParamValue(): Value
	{
		return new NaturalNumberValue();
	}

	public function createDetailPage(array $query): ?ContentPage
	{
		return new ConferenceEditorCRUDPage();
	}

}
?>
