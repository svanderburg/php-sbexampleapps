<?php
namespace SBExampleApps\Auth\Model\Page;
use SBLayout\Model\Page\Content\Contents;
use SBExampleApps\Auth\Model\AuthorizationManager;

class RestrictedHiddenOperationPage extends RestrictedOperationPage
{
	public function __construct(string $title, Contents $contents, AuthorizationManager $authorizationManager, string $operationParam = "__operation")
	{
		parent::__construct($title, $contents, $authorizationManager, $operationParam);
	}

	public function checkVisibility(): bool
	{
		return false;
	}
}
?>
