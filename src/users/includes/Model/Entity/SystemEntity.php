<?php
namespace SBExampleApps\Users\Model\Entity;
use Exception;
use PDO;
use PDOStatement;

class SystemEntity
{
	public static function queryAll(PDO $dbh): PDOStatement
	{
		$stmt = $dbh->prepare("select * from system order by SYSTEM_ID");
		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryOne(PDO $dbh, string $id): PDOStatement
	{
		$stmt = $dbh->prepare("select * from system where SYSTEM_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function insert(PDO $dbh, array $system): void
	{
		$stmt = $dbh->prepare("insert into system values (?, ?)");
		if(!$stmt->execute(array($system['SYSTEM_ID'], $system['Description'])))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function update(PDO $dbh, array $system, string $id): void
	{
		$stmt = $dbh->prepare("update system set ".
			"SYSTEM_ID = ?, ".
			"Description = ? ".
			"where SYSTEM_ID = ?");
		if(!$stmt->execute(array($system['SYSTEM_ID'], $system['Description'], $id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function remove(PDO $dbh, string $id): void
	{
		$stmt = $dbh->prepare("delete from system where SYSTEM_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
}
?>
