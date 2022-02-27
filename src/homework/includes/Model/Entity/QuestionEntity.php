<?php
namespace SBExampleApps\Homework\Model\Entity;
use Exception;
use PDO;
use PDOStatement;

class QuestionEntity
{
	public static function queryOne(PDO $dbh, string $testId, int $questionId): PDOStatement
	{
		$stmt = $dbh->prepare("select * from question where QUESTION_ID = ? and TEST_ID = ?");

		if(!$stmt->execute(array($questionId, $testId)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function querySuccessiveQuestion(PDO $dbh, string $testId, int $questionId): PDOStatement
	{
		$stmt = $dbh->prepare("select min(QUESTION_ID) from question where TEST_ID = ? and QUESTION_ID > ?");
		if(!$stmt->execute(array($testId, $questionId)))
			throw new Exception($stmt->errorInfo()[2]);

		if(($row = $stmt->fetch()) !== false)
		{
			$stmt = $dbh->prepare("select * from question where QUESTION_ID = ? and TEST_ID = ?");

			if(!$stmt->execute(array($row[0], $testId)))
				throw new Exception($stmt->errorInfo()[2]);
		}

		return $stmt;
	}

	public static function nextQuestionId(PDO $dbh, string $testId): int
	{
		$stmt = $dbh->prepare("select MAX(QUESTION_ID) from question where TEST_ID = ?");

		if(!$stmt->execute(array($testId)))
		{
			$dbh->rollBack();
			throw new Exception($stmt->errorInfo()[2]);
		}

		if(($row = $stmt->fetch()) === false)
			return 1;
		else
			return (int)($row[0] + 1);
	}

	public static function insert(PDO $dbh, array $question): int
	{
		$dbh->beginTransaction();

		$questionId = QuestionEntity::nextQuestionId($dbh, $question['TEST_ID']);

		$stmt = $dbh->prepare("insert into question values (?, ?, ?, ?, ?)");

		if(!$stmt->execute(array($questionId, $question['Question'], $question['Answer'], $question['Exact'] == "1" ? 1 : 0, $question['TEST_ID'])))
		{
			$dbh->rollBack();
			throw new Exception($stmt->errorInfo()[2]);
		}

		$dbh->commit();

		return $questionId;
	}

	public static function update(PDO $dbh, array $question, string $testId, int $questionId): void
	{
		$stmt = $dbh->prepare("update question set ".
			"QUESTION_ID = ?, ".
			"Question = ?, ".
			"Answer = ?, ".
			"Exact = ?, ".
			"TEST_ID = ? ".
			"where QUESTION_ID = ? and TEST_ID = ?");

		if(!$stmt->execute(array($question['QUESTION_ID'], $question['Question'], $question['Answer'], $question['Exact'], $question['TEST_ID'], $questionId, $testId)))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function remove(PDO $dbh, string $testId, int $questionId): void
	{
		$stmt = $dbh->prepare("delete from question where QUESTION_ID = ? and TEST_ID = ?");

		if(!$stmt->execute(array($questionId, $testId)))
			throw new Exception($stmt->errorInfo()[2]);
	}
}
?>
