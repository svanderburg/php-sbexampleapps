<?php
namespace SBExampleApps\Auth\Model\Page;
use SBLayout\Model\Page\HiddenStaticContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBExampleApps\Auth\Model\AuthorizationManager;

class AuthorizationPage extends HiddenStaticContentPage
{
	private AuthorizationManager $authorizationManager;

	private string $loginTitle;

	private string $logoutTitle;

	public function __construct(AuthorizationManager $authorizationManager, string $loginTitle, string $logoutTitle, array $subPages = array())
	{
		parent::__construct("Authorization", new Contents(dirname(__FILE__)."/../../View/HTML/auth.php",  dirname(__FILE__)."/../../Controller/auth.php"), $subPages);
		unset($this->title); // Allows the __get function to dynamically provide a title
		$this->authorizationManager = $authorizationManager;
		$this->loginTitle = $loginTitle;
		$this->logoutTitle = $logoutTitle;
	}

	public function __get(string $name): mixed
	{
		if($name == "title")
		{
			if($this->authorizationManager->authenticated)
				return $this->logoutTitle;
			else
				return $this->loginTitle;
		}
		else
			return $this->$name;
	}
}
?>
