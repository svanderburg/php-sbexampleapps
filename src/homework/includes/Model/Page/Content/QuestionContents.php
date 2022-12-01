<?php
namespace SBExampleApps\Homework\Model\Page\Content;
use SBLayout\Model\Page\Content\Contents;

class QuestionContents extends Contents
{
	public function __construct()
	{
		parent::__construct("tests/test/questions/question.php", "tests/test/questions/question.php");
	}
}
?>
