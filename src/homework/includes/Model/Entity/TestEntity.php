<?php
namespace SBExampleApps\Homework\Model\Entity;
use Exception;
use PDO;
use PDOStatement;

class TestEntity
{
	public static function queryAll(PDO $dbh): PDOStatement
	{
		$stmt = $dbh->prepare("select * from test order by TEST_ID");
		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryOne(PDO $dbh, string $id): PDOStatement
	{
		$stmt = $dbh->prepare("select * from test where TEST_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryAllQuestions(PDO $dbh, string $id): PDOStatement
	{
		$stmt = $dbh->prepare("select * from question where TEST_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function insert(PDO $dbh, array $test): void
	{
		$stmt = $dbh->prepare("insert into test values (?, ?)");
		if(!$stmt->execute(array($test['TEST_ID'], $test['Title'])))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function update(PDO $dbh, array $test, string $id): void
	{
		$stmt = $dbh->prepare("update test set ".
			"TEST_ID = ?, ".
			"Title = ? ".
			"where TEST_ID = ?");
		if(!$stmt->execute(array($test['TEST_ID'], $test['Title'], $id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function remove(PDO $dbh, string $id): void
	{
		$stmt = $dbh->prepare("delete from test where TEST_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
}
?>
