<?php
namespace SBExampleApps\Homework\Model\Entity;
use Exception;
use PDO;

class TestEntity
{
	public static function queryAll(PDO $dbh)
	{
		$stmt = $dbh->prepare("select * from test order by TEST_ID");
		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryOne(PDO $dbh, $id)
	{
		$stmt = $dbh->prepare("select * from test where TEST_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryAllQuestions(PDO $dbh, $id)
	{
		$stmt = $dbh->prepare("select * from question where TEST_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function insert(PDO $dbh, array $test)
	{
		$stmt = $dbh->prepare("insert into test values (?, ?)");
		if(!$stmt->execute(array($test['TEST_ID'], $test['Title'])))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function update(PDO $dbh, array $test, $id)
	{
		$stmt = $dbh->prepare("update test set ".
			"TEST_ID = ?, ".
			"Title = ? ".
			"where TEST_ID = ?");
		if(!$stmt->execute(array($test['TEST_ID'], $test['Title'], $id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function remove(PDO $dbh, $id)
	{
		$stmt = $dbh->prepare("delete from test where TEST_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
}
?>
