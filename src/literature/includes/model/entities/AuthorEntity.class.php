<?php
class AuthorEntity
{
	public static function queryAll(PDO $dbh)
	{
		$stmt = $dbh->prepare("select * from author order by AUTHOR_ID");
		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryOne(PDO $dbh, $id)
	{
		$stmt = $dbh->prepare("select * from author where AUTHOR_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function querySummary(PDO $dbh)
	{
		$stmt = $dbh->prepare("select AUTHOR_ID, LastName from author order by LastName");
		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function nextAuthorId(PDO $dbh)
	{
		$stmt = $dbh->prepare("select MAX(AUTHOR_ID) from author");
		if(!$stmt->execute())
		{
			throw new Exception($stmt->errorInfo()[2]);
			$dbh->rollBack();
		}
		
		if(($row = $stmt->fetch()) === false)
			return 1;
		else
			return $row[0] + 1;
	}

	public static function insert(PDO $dbh, array $author)
	{
		$dbh->beginTransaction();

		$authorId = AuthorEntity::nextAuthorId($dbh);

		$stmt = $dbh->prepare("insert into author values (?, ?, ?, ?)");
		if(!$stmt->execute(array($authorId, $author['FirstName'], $author['LastName'], $author['Homepage'])))
		{
			$dbh->rollBack();
			throw new Exception($stmt->errorInfo()[2]);
		}

		$dbh->commit();

		return $authorId;
	}
	
	public static function update(PDO $dbh, array $author, $id)
	{
		$stmt = $dbh->prepare("update author set ".
			"FirstName = ?, ".
			"LastName = ?, ".
			"Homepage = ? ".
			"where AUTHOR_ID = ?");
		if(!$stmt->execute(array($author['FirstName'], $author['LastName'], $author['Homepage'], $id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function remove(PDO $dbh, $id)
	{
		$stmt = $dbh->prepare("delete from author where AUTHOR_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
}
?>
