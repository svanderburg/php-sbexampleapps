<?php
namespace SBExampleApps\Users\Model\Page;
use PDO;
use SBLayout\Model\PageNotFoundException;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\Page\CRUDDetailPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Auth\Model\Page\RestrictedOperationPage;
use SBExampleApps\Users\Model\Entity\SystemEntity;
use SBExampleApps\Users\Model\Page\Content\SystemContents;

class SystemCRUDPage extends CRUDDetailPage
{
	public AuthorizationManager $authorizationManager;

	public array $entity;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, string $systemId)
	{
		parent::__construct("System", new SystemContents(), array(
			"update_system" => new RestrictedOperationPage("Update system", new SystemContents(), $authorizationManager),
			"delete_system" => new RestrictedOperationPage("Delete system", new SystemContents(), $authorizationManager)
		));
		$this->authorizationManager = $authorizationManager;

		$stmt = SystemEntity::queryOne($dbh, $systemId);

		if(($entity = $stmt->fetch()) === false)
			throw new PageNotFoundException("Cannot find system with id: ".$systemId);

		$this->title = $entity["Description"];
		$this->entity = $entity;
	}

	public function checkAccessibility(): bool
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
