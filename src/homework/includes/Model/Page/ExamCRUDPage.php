<?php
namespace SBExampleApps\Homework\Model\Page;
use PDO;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\ParameterMap;
use SBData\Model\Value\Value;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\Page\StaticContentCRUDPage;
use SBExampleApps\Homework\Model\CRUD\ExamCRUDModel;

class ExamCRUDPage extends StaticContentCRUDPage
{
	public PDO $dbh;

	public function __construct(PDO $dbh, array $subPages = array())
	{
		parent::__construct("Exam",
			/* Key parameters */
			new ParameterMap(array(
				"testId" => new Value(true, 255)
			)),
			/* Request parameters */
			new ParameterMap(),
			/* Default contents */
			new Contents("crud/exam.php"),
			/* Error contents */
			new Contents("crud/error.php"),

			/* Contents per operation */
			array(),
			$subPages);

		$this->dbh = $dbh;
	}

	public function constructCRUDModel(): CRUDModel
	{
		return new ExamCRUDModel($this, $this->dbh);
	}
}
?>
