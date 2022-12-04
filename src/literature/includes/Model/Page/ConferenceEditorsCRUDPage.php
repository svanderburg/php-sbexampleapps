<?php
namespace SBExampleApps\Literature\Model\Page;
use SBCrud\Model\Page\CRUDDetailPage;
use SBCrud\Model\Page\OperationPage;
use SBExampleApps\Literature\Model\Page\Content\ConferenceEditorContents;

class ConferenceEditorsCRUDPage extends CRUDDetailPage
{
	public function __construct()
	{
		parent::__construct("Editors", new ConferenceEditorContents(), array(
			"insert_conference_author" => new OperationPage("Add editor", new ConferenceEditorContents()),
			"delete_conference_author" => new OperationPage("Delete editor", new ConferenceEditorContents())
		));
	}
}
?>
