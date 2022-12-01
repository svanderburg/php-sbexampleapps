<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\PageNotFoundException;
use SBCrud\Model\Page\CRUDDetailPage;
use SBCrud\Model\Page\OperationPage;
use SBExampleApps\Literature\Model\Page\Content\ConferenceContents;
use SBExampleApps\Literature\Model\Entity\ConferenceEntity;

class ConferenceCRUDPage extends CRUDDetailPage
{
	public function __construct(PDO $dbh, int $conferenceId)
	{
		parent::__construct("Conference", new ConferenceContents(), array(
			"update_conference" => new OperationPage("Update conference", new ConferenceContents()),
			"delete_conference" => new OperationPage("Update conference", new ConferenceContents()),
			"insert_conference_author" => new OperationPage("Insert author link", new ConferenceContents()),
			"delete_conference_author" => new OperationPage("Delete author link", new ConferenceContents())
		), array(
			"papers" => new PapersCRUDPage($dbh)
		));

		$stmt = ConferenceEntity::queryOne($dbh, $conferenceId);

		if(($entity = $stmt->fetch()) === false)
			throw new PageNotFoundException("Cannot find conference with id:".$conferenceId);

		$this->entity = $entity;
		$this->title = $entity["Name"];
	}
}
?>
