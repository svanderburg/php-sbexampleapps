<?php
require_once("layout/model/page/HiddenStaticContentPage.class.php");
require_once(dirname(__FILE__)."/../AuthorizationManager.class.php");

class AuthorizationPage extends HiddenStaticContentPage
{
	private $authorizationManager;

	private $loginTitle;

	private $logoutTitle;

	public function __construct(AuthorizationManager $authorizationManager, $loginTitle, $logoutTitle, array $subPages = null)
	{
		parent::__construct("Authorization", new Contents(dirname(__FILE__)."/../../view/html/auth.inc.php",  dirname(__FILE__)."/../../controller/auth.inc.php"), $subPages);
		unset($this->title); // Allows the __get function to dynamically provide a title
		$this->authorizationManager = $authorizationManager;
		$this->loginTitle = $loginTitle;
		$this->logoutTitle = $logoutTitle;
	}

	public function __get($name)
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
