<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\Page\ContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Value\Value;
use SBData\Model\Value\NaturalNumberValue;
use SBCrud\Model\Page\CRUDMasterPage;
use SBCrud\Model\Page\OperationPage;
use SBCrud\Model\Page\HiddenOperationPage;
use SBExampleApps\Literature\Model\Entity\PaperEntity;
use SBExampleApps\Literature\Model\Page\Content\PaperContents;

class PapersCRUDPage extends CRUDMasterPage
{
	public function __construct(PDO $dbh)
	{
		parent::__construct("Papers", "paperId", new Contents("conferences/conference/papers.php", "conferences/conference/papers.php"), array(
			"create_paper" => new OperationPage("Create paper", new PaperContents()),
			"insert_paper" => new HiddenOperationPage("Insert paper", new PaperContents())
		));

		$this->dbh = $dbh;
	}

	public function createParamValue(): Value
	{
		return new NaturalNumberValue(true);
	}

	public function createDetailPage(array $query): ?ContentPage
	{
		return new PaperCRUDPage($this->dbh, $query["conferenceId"], $query["paperId"]);
	}
}
?>
