<?php
namespace SBExampleApps\Homework\Model\Page;
use SBCrud\Model\Page\CRUDDetailPage;
use SBCrud\Model\Page\HiddenOperationPage;
use SBExampleApps\Homework\Model\Page\Content\ExamContents;

class ExamCRUDPage extends CRUDDetailPage
{
	public function __construct()
	{
		parent::__construct("Exam", new ExamContents(), array(
			"submit_answer" => new HiddenOperationPage("Submit answer", new ExamContents())
		));
	}
}
?>
