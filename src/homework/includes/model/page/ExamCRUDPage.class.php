<?php
require_once("crud/model/page/StaticContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/ExamCRUDModel.class.php");

class ExamCRUDPage extends StaticContentCRUDPage
{
	public $dbh;

	public function __construct(PDO $dbh, array $subPages = null)
	{
		parent::__construct("Exam",
			/* Key fields */
			array(
				"testId" => new TextField("Id", true, 20, 255)
			),
			/* Default contents */
			new Contents("crud/exam.inc.php"),
			/* Error contents */
			new Contents("crud/error.inc.php"),

			/* Contents per operation */
			array(),
			$subPages);

		$this->dbh = $dbh;
	}

	public function constructCRUDModel()
	{
		return new ExamCRUDModel($this, $this->dbh);
	}
}
?>
