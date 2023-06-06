<?php
namespace SBExampleApps\Literature\Model\Page;
use SBCrud\Model\Page\CRUDDetailPage;
use SBCrud\Model\Page\OperationPage;
use SBExampleApps\Literature\Model\Page\Content\ConferenceEditorContents;

class ConferenceEditorCRUDPage extends CRUDDetailPage
{
	public function __construct()
	{
		parent::__construct("Editor", new ConferenceEditorContents(), array(
			"delete_conference_author" => new OperationPage("Delete editor", new ConferenceEditorContents())
		));
	}
}
?>
