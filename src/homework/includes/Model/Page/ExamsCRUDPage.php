<?php
namespace SBExampleApps\Homework\Model\Page;
use SBLayout\Model\Page\ContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Value\Value;
use SBCrud\Model\Page\MasterPage;

class ExamsCRUDPage extends MasterPage
{
	public function __construct()
	{
		parent::__construct("Exams", "testId", new Contents("exams.php", "exams.php"));
	}

	public function createParamValue(): Value
	{
		return new Value(true, 255);
	}

	public function createDetailPage(array $query): ?ContentPage
	{
		return new ExamCRUDPage();
	}
}
?>
