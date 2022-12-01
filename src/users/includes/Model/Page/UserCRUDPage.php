<?php
namespace SBExampleApps\Users\Model\Page;
use PDO;
use SBLayout\Model\PageNotFoundException;
use SBCrud\Model\Page\CRUDDetailPage;
use SBExampleApps\Users\Model\Page\Content\UserContents;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Auth\Model\Page\RestrictedOperationPage;
use SBExampleApps\Users\Model\Entity\UserEntity;

class UserCRUDPage extends CRUDDetailPage
{
	public array $entity;

	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, string $username)
	{
		parent::__construct("User", new UserContents(), array(
			"update_user" => new RestrictedOperationPage("Update user", new UserContents(), $authorizationManager),
			"delete_user" => new RestrictedOperationPage("Delete user", new UserContents(), $authorizationManager),
			"insert_user_system" => new RestrictedOperationPage("Attach system link", new UserContents(), $authorizationManager),
			"delete_user_system" => new RestrictedOperationPage("Remove system link", new UserContents(), $authorizationManager)
		));
		$this->authorizationManager = $authorizationManager;

		$stmt = UserEntity::queryOne($dbh, $username);

		if(($entity = $stmt->fetch()) === false)
			throw new PageNotFoundException("Cannot find user with username: ".$username);

		// We deliberately don't update the title for this since it is privacy sensitive information
		$this->entity = $entity;
	}

	public function checkAccessibility(): bool
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
