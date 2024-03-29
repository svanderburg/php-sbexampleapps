<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\PageNotFoundException;
use SBLayout\Model\Page\PageAlias;
use SBCrud\Model\Page\CRUDDetailPage;
use SBCrud\Model\Page\OperationPage;
use SBCrud\Model\Page\HiddenOperationPage;
use SBExampleApps\Literature\Model\Page\Content\ConferenceContents;
use SBExampleApps\Literature\Model\Entity\ConferenceEntity;

class ConferenceCRUDPage extends CRUDDetailPage
{
	public function __construct(PDO $dbh, int $conferenceId)
	{
		parent::__construct("Conference", new ConferenceContents(), array(
			"update_conference" => new HiddenOperationPage("Update conference", new ConferenceContents()),
			"delete_conference" => new OperationPage("Delete conference", new ConferenceContents()),
			"insert_conference_author" => new HiddenOperationPage("Insert author link", new ConferenceContents()),
			"delete_conference_author" => new HiddenOperationPage("Delete author link", new ConferenceContents())
		), array(
			"conference" => new PageAlias("Conference", "conferences/".$conferenceId),
			"papers" => new PapersCRUDPage($dbh),
			"editors" => new ConferenceEditorsCRUDPage(),
		));

		$stmt = ConferenceEntity::queryOne($dbh, $conferenceId);

		if(($entity = $stmt->fetch()) === false)
			throw new PageNotFoundException("Cannot find conference with id:".$conferenceId);

		$this->entity = $entity;
		$this->title = $entity["Name"];
	}
}
?>
