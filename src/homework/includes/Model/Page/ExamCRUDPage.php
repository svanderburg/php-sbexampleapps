<?php
namespace SBExampleApps\Homework\Model\Page;
use PDO;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Field\TextField;
use SBCrud\Model\Page\StaticContentCRUDPage;
use SBExampleApps\Homework\Model\CRUD\ExamCRUDModel;

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
			new Contents("crud/exam.php"),
			/* Error contents */
			new Contents("crud/error.php"),

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
