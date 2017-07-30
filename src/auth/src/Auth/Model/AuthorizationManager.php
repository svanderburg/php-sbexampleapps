<?php
namespace SBExampleApps\Auth\Model;
use PDO;
use SBExampleApps\Auth\Model\Entity\AuthorizationCheckEntity;

class AuthorizationManager
{
	private $dbh;

	private $systemId;

	public $authenticated;

	public function __construct(PDO $dbh, $systemId)
	{
		$this->dbh = $dbh;
		$this->systemId = $systemId;
		$this->authenticated = false;
	}

	public function checkCredentialsIfLoggedIn()
	{
		if(array_key_exists("PHPSESSID", $_COOKIE))
		{
			if(session_status() == PHP_SESSION_NONE)
				session_start();

			if(array_key_exists("Username", $_SESSION) && array_key_exists("Password", $_SESSION))
				$this->authenticated = AuthorizationCheckEntity::checkAuthorization($this->dbh, $_SESSION["Username"], $_SESSION["Password"], $this->systemId);
			else
				$this->authenticated = false;
		}
	}

	public function login(array $credentials)
	{
		$this->authenticated = AuthorizationCheckEntity::checkAuthorization($this->dbh, $credentials["Username"], $credentials["Password"], $this->systemId);

		if($this->authenticated)
		{
			if(session_status() == PHP_SESSION_NONE)
				session_start();

			$_SESSION["Username"] = $credentials["Username"];
			$_SESSION["Password"] = $credentials["Password"];
		}
	}

	public function logout()
	{
		$params = session_get_cookie_params();
		setcookie(session_name(), '', 0, $params['path'], $params['domain'], $params['secure'], isset($params['httponly']));
		$this->authenticated = false;
	}
}
?>
