<?php
class UserEntity
{
	public static function queryAll(PDO $dbh)
	{
		$stmt = $dbh->prepare("select Username, FullName from user order by Username");
		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryOne(PDO $dbh, $userName)
	{
		$stmt = $dbh->prepare("select Username, FullName from user where Username = ?");
		if(!$stmt->execute(array($userName)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function insert(PDO $dbh, array $user)
	{
		$stmt = $dbh->prepare("insert into user values (?, ?, ?)");
		if(!$stmt->execute(array($user['Username'], password_hash($user['Password'], PASSWORD_DEFAULT), $user['FullName'])))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function update(PDO $dbh, array $user, $userName)
	{
		if(array_key_exists("Password", $user) && $user["Password"] !== "")
		{
			$stmt = $dbh->prepare("update user set ".
				"Username = ?, ".
				"Password = ?, ".
				"FullName = ? ".
				"where Username = ?");
			if(!$stmt->execute(array($user['Username'], password_hash($user['Password'], PASSWORD_DEFAULT), $user['FullName'], $userName)))
				throw new Exception($stmt->errorInfo()[2]);
		}
		else
		{
			$stmt = $dbh->prepare("update user set ".
				"Username = ?, ".
				"FullName = ? ".
				"where Username = ?");
			if(!$stmt->execute(array($user['Username'], $user['FullName'], $userName)))
				throw new Exception($stmt->errorInfo()[2]);
		}
	}
	
	public static function remove(PDO $dbh, $userName)
	{
		$stmt = $dbh->prepare("delete from user where Username = ?");
		if(!$stmt->execute(array($userName)))
			throw new Exception($stmt->errorInfo()[2]);
	}

	public static function queryAllAuthorizedSystems(PDO $dbh, $userName)
	{
		$stmt = $dbh->prepare("select system.SYSTEM_ID, system.Description ".
			"from user_system ".
			"inner join system on user_system.SYSTEM_ID = system.SYSTEM_ID ".
			"where user_system.Username = ? ".
			"order by system.SYSTEM_ID");
		if(!$stmt->execute(array($userName)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function insertAuthorizedSystem(PDO $dbh, $userName, $systemId)
	{
		$stmt = $dbh->prepare("insert into user_system values (?, ?)");

		if(!$stmt->execute(array($userName, $systemId)))
			throw new Exception($stmt->errorInfo()[2]);
	}

	public static function removeAuthorizedSystem(PDO $dbh, $userName, $systemId)
	{
		$stmt = $dbh->prepare("delete from user_system where Username = ? and SYSTEM_ID = ?");

		if(!$stmt->execute(array($userName, $systemId)))
			throw new Exception($stmt->errorInfo()[2]);
	}
}
?>
