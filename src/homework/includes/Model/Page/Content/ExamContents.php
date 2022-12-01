<?php
namespace SBExampleApps\Homework\Model\Page\Content;
use SBLayout\Model\Page\Content\Contents;

class ExamContents extends Contents
{
	public function __construct()
	{
		parent::__construct("exams/exam.php", "exams/exam.php");
	}
}
?>
