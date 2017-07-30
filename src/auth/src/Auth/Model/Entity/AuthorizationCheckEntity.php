<?php
namespace SBExampleApps\Auth\Model\Entity;
use Exception;
use PDO;

class AuthorizationCheckEntity
{
	public static function checkAuthorization(PDO $dbh, $userName, $password, $systemId)
	{
		$stmt = $dbh->prepare("select user.Password ".
			"from user ".
			"inner join user_system on user.Username = user_system.Username ".
			"where user_system.Username = ? and user_system.SYSTEM_ID = ?");

		if($stmt->execute(array($userName, $systemId)))
		{
			if(($row = $stmt->fetch()) === false)
				return false;
			else
				return password_verify($password, $row["Password"]);
		}
		else
			throw new Exception($stmt->errorInfo()[2]);
	}
}
?>
