<?php
namespace SBExampleApps\Literature\Model\Page;
use SBCrud\Model\Page\CRUDDetailPage;
use SBCrud\Model\Page\OperationPage;
use SBExampleApps\Literature\Model\Page\Content\PaperAuthorContents;

class PaperAuthorCRUDPage extends CRUDDetailPage
{
	public function __construct()
	{
		parent::__construct("Paper author", new PaperAuthorContents(), array(
			"delete_paper_author" => new OperationPage("Delete author", new PaperAuthorContents())
		));
	}
}
?>
