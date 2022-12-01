<?php
namespace SBExampleApps\Auth\Model\Page;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\Page\OperationPage;
use SBExampleApps\Auth\Model\AuthorizationManager;

class RestrictedOperationPage extends OperationPage
{
	public AuthorizationManager $authorizationManager;

	public function __construct(string $title, Contents $contents, AuthorizationManager $authorizationManager, string $operationParam = "__operation")
	{
		parent::__construct($title, $contents, $operationParam);
		$this->authorizationManager = $authorizationManager;
	}

	public function checkAccessibility(): bool
	{
		return $this->authorizationManager->authenticated;
	}
}
?>
