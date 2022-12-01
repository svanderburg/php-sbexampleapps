<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\PageNotFoundException;
use SBLayout\Model\Page\HiddenStaticContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\Page\CRUDDetailPage;
use SBCrud\Model\Page\OperationPage;
use SBExampleApps\Literature\Model\Page\Content\PaperContents;
use SBExampleApps\Literature\Model\Entity\PaperEntity;

class PaperCRUDPage extends CRUDDetailPage
{
	public array $entity;

	public function __construct(PDO $dbh, int $conferenceId, int $paperId)
	{
		parent::__construct("Paper", new PaperContents(), array(
			"update_paper" => new OperationPage("Update paper", new PaperContents()),
			"delete_paper" => new OperationPage("Delete paper", new PaperContents()),
			"insert_paper_author" => new OperationPage("Insert paper author", new PaperContents()),
			"delete_paper_author" => new OperationPage("Update paper author", new PaperContents()),
			"delete_paper_pdf" => new OperationPage("Delete paper PDF", new PaperContents())
		), array(
			"reference" => new HiddenStaticContentPage("Reference", new Contents("conferences/conference/papers/paper/reference.php", null, array("citation.css"), array("publications.js")))
		));

		$stmt = PaperEntity::queryOne($dbh, $paperId, $conferenceId);

		if(($entity = $stmt->fetch()) === false)
			throw new PageNotFoundException("Cannot find paper with id: ".$paperId);

		$this->entity = $entity;
		$this->title = $entity["Title"];
	}
}
?>
