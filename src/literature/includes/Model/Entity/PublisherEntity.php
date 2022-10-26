<?php
namespace SBExampleApps\Literature\Model\Entity;
use Exception;
use PDO;
use PDOStatement;

class PublisherEntity
{
	public static function queryAll(PDO $dbh): PDOStatement
	{
		$stmt = $dbh->prepare("select * from publisher order by PUBLISHER_ID");
		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryOne(PDO $dbh, string $id): PDOStatement
	{
		$stmt = $dbh->prepare("select * from publisher where PUBLISHER_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function querySummary(PDO $dbh): PDOStatement
	{
		$stmt = $dbh->prepare("select PUBLISHER_ID, Name from publisher order by Name");
		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryOneSummary(PDO $dbh, string $id): PDOStatement
	{
		$stmt = $dbh->prepare("select PUBLISHER_ID, Name from publisher where PUBLISHER_ID = ? order by Name");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function insert(PDO $dbh, array $publisher): void
	{
		$stmt = $dbh->prepare("insert into publisher values (?, ?)");
		if(!$stmt->execute(array($publisher['PUBLISHER_ID'], $publisher['Name'])))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function update(PDO $dbh, array $publisher, string $id): void
	{
		$stmt = $dbh->prepare("update publisher set ".
			"PUBLISHER_ID = ?, ".
			"Name = ? ".
			"where PUBLISHER_ID = ?");
		if(!$stmt->execute(array($publisher['PUBLISHER_ID'], $publisher['Name'], $id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function remove(PDO $dbh, string $id): void
	{
		$stmt = $dbh->prepare("delete from publisher where PUBLISHER_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
}
?>
