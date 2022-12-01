<?php
namespace SBExampleApps\Portal\Model\Page;
use PDO;
use SBLayout\Model\PageNotFoundException;
use SBCrud\Model\Page\CRUDDetailPage;
use SBCrud\Model\Page\OperationPage;
use SBExampleApps\Portal\Model\Entity\NewsMessageEntity;
use SBExampleApps\Portal\Model\Page\Content\NewsMessageContents;

class NewsMessageCRUDPage extends CRUDDetailPage
{
	public array $entity;

	public function __construct(PDO $dbh, string $messageId)
	{
		parent::__construct("News message", new NewsMessageContents(), array(
			"update_newsmessage" => new OperationPage("Update news message", new NewsMessageContents()),
			"remove_newsmessage" => new OperationPage("Remove news message", new NewsMessageContents())
		));

		$stmt = NewsMessageEntity::queryOne($dbh, $GLOBALS["query"]["messageId"]);
		if(($entity = $stmt->fetch()) === false)
			throw new PageNotFoundException("Cannot find news message with id: ".$GLOBALS["query"]["messageId"]);

		$this->title = $entity["Title"];
		$this->entity = $entity;
	}
}
?>
