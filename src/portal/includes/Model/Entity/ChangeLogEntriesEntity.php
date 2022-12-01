<?php
namespace SBExampleApps\Portal\Model\Entity;
use Exception;
use PDO;
use PDOStatement;

class ChangeLogEntriesEntity
{
	public static function queryAll(PDO $dbh): PDOStatement
	{
		$stmt = $dbh->prepare("select LOG_ID, Date, Summary, LOG_ID as old_LOG_ID from changelogentries order by LOG_ID desc");

		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryOne(PDO $dbh, string $logId): PDOStatement
	{
		$stmt = $dbh->prepare("select * from changelogentries where LOG_ID = ?");

		if(!$stmt->execute(array($logId)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function insert(PDO $dbh, array $entry): PDOStatement
	{
		$stmt = $dbh->prepare("insert into changelogentries values (?, ?, ?)");
	
		if(!$stmt->execute(array($entry["LOG_ID"], $entry["Date"], $entry["Summary"])))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function update(PDO $dbh, array $entry, string $id): PDOStatement
	{
		$stmt = $dbh->prepare("update changelogentries set ".
			"LOG_ID = ?, ".
			"Date = ?, ".
			"Summary = ? ".
			"where LOG_ID = ?");

		if(!$stmt->execute(array($entry["LOG_ID"], $entry["Date"], $entry["Summary"], $id)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function remove(PDO $dbh, string $id): PDOStatement
	{
		$stmt = $dbh->prepare("delete from changelogentries where LOG_ID = ?");

		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}
}
?>
