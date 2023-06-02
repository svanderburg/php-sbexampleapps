<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\PageNotFoundException;
use SBLayout\Model\Page\PageAlias;
use SBLayout\Model\Page\StaticContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\Page\CRUDDetailPage;
use SBCrud\Model\Page\OperationPage;
use SBCrud\Model\Page\HiddenOperationPage;
use SBExampleApps\Literature\Model\Page\Content\PaperContents;
use SBExampleApps\Literature\Model\Entity\PaperEntity;

class PaperCRUDPage extends CRUDDetailPage
{
	public array $entity;

	public function __construct(PDO $dbh, int $conferenceId, int $paperId)
	{
		parent::__construct("Paper", new PaperContents(), array(
			"update_paper" => new HiddenOperationPage("Update paper", new PaperContents()),
			"delete_paper" => new OperationPage("Delete paper", new PaperContents()),
			"insert_paper_author" => new HiddenOperationPage("Insert paper author", new PaperContents()),
			"delete_paper_author" => new HiddenOperationPage("Update paper author", new PaperContents()),
			"delete_paper_pdf" => new OperationPage("Delete paper PDF", new PaperContents())
		), array(
			"paper" => new PageAlias("Paper", "conferences/".$conferenceId."/papers/".$paperId),
			"authors" => new PaperAuthorsCRUDPage(),
			"reference" => new StaticContentPage("Reference", new Contents("conferences/conference/papers/paper/reference.php", null, array("citation.css"), array("publications.js")))
		));

		$stmt = PaperEntity::queryOne($dbh, $paperId, $conferenceId);

		if(($entity = $stmt->fetch()) === false)
			throw new PageNotFoundException("Cannot find paper with id: ".$paperId);

		$this->entity = $entity;
		$this->title = $entity["Title"];
	}
}
?>
