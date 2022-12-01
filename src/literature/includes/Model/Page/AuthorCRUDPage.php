<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\PageNotFoundException;
use SBCrud\Model\Page\CRUDDetailPage;
use SBCrud\Model\Page\OperationPage;
use SBExampleApps\Literature\Model\Page\Content\AuthorContents;
use SBExampleApps\Literature\Model\Entity\AuthorEntity;

class AuthorCRUDPage extends CRUDDetailPage
{
	public array $entity;

	public function __construct(PDO $dbh, int $authorId)
	{
		parent::__construct("Author", new AuthorContents(), array(
			"update_author" => new OperationPage("Update author", new AuthorContents()),
			"delete_author" => new OperationPage("Delete author", new AuthorContents())
		));

		$stmt = AuthorEntity::queryOne($dbh, $authorId);
		if(($entity = $stmt->fetch()) === false)
			throw new PageNotFoundException("Cannot find author with id".$authorId);

		$this->entity = $entity;
		// Do not change title, because it contains privacy sensitive information
	}
}
?>
