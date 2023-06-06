<?php
namespace SBExampleApps\Literature\Model\Page;
use SBLayout\Model\Page\ContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Value\Value;
use SBData\Model\Value\NaturalNumberValue;
use SBCrud\Model\Page\CRUDMasterPage;
use SBCrud\Model\Page\HiddenOperationPage;
use SBExampleApps\Literature\Model\Page\Content\PaperAuthorContents;

class PaperAuthorsCRUDPage extends CRUDMasterPage
{
	public function __construct()
	{
		parent::__construct("Authors", "authorId", new Contents("conferences/conference/papers/paper/authors.php", "conferences/conference/papers/paper/authors.php"), array(
			"insert_paper_author" => new HiddenOperationPage("Insert author", new PaperAuthorContents())
		));
	}

	public function createParamValue(): Value
	{
		return new NaturalNumberValue();
	}

	public function createDetailPage(array $query): ?ContentPage
	{
		return new PaperAuthorCRUDPage();
	}
}
?>
