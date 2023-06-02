<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\PageNotFoundException;
use SBCrud\Model\Page\CRUDDetailPage;
use SBCrud\Model\Page\OperationPage;
use SBCrud\Model\Page\HiddenOperationPage;
use SBExampleApps\Literature\Model\Entity\PublisherEntity;
use SBExampleApps\Literature\Model\Page\Content\PublisherContents;

class PublisherCRUDPage extends CRUDDetailPage
{
	public array $entity;

	public function __construct(PDO $dbh, string $publisherId)
	{
		parent::__construct("Publisher", new PublisherContents(), array(
			"update_publisher" => new HiddenOperationPage("Update publisher", new PublisherContents()),
			"delete_publisher" => new OperationPage("Delete publisher", new PublisherContents())
		));

		$stmt = PublisherEntity::queryOne($dbh, $publisherId);

		if(($entity = $stmt->fetch()) === false)
			throw new PageNotFoundException("Cannot find publisher with id: ".$publisherId);

		$this->entity = $entity;
		$this->title = $entity["Name"];
	}
}
?>
