<?php
namespace SBExampleApps\Literature\Model\Page;
use SBCrud\Model\Page\CRUDDetailPage;
use SBCrud\Model\Page\OperationPage;
use SBCrud\Model\Page\HiddenOperationPage;
use SBExampleApps\Literature\Model\Page\Content\PaperAuthorsContents;

class PaperAuthorsCRUDPage extends CRUDDetailPage
{
	public function __construct()
	{
		parent::__construct("Authors", new PaperAuthorsContents(), array(
			"insert_paper_author" => new HiddenOperationPage("Add author", new PaperAuthorsContents()),
			"delete_paper_author" => new OperationPage("Delete author", new PaperAuthorsContents()),
		));
	}
}
?>
