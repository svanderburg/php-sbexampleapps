<?php
class NewsMessageEntity
{
	public static function queryAll(PDO $dbh, $page)
	{
		$offset = intval($page * 10);

		$stmt = $dbh->prepare("select * from newsmessages order by Date desc, MESSAGE_ID desc limit ?, 10");
		$stmt->bindParam(1, $offset, PDO::PARAM_INT);

		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryOne(PDO $dbh, $id)
	{
		$stmt = $dbh->prepare("select * from newsmessages where MESSAGE_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryNumOfNewsMessages(PDO $dbh)
	{
		$stmt = $dbh->prepare("select count(*) from newsmessages");
		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryLatestDate(PDO $dbh)
	{
		$stmt = $dbh->prepare("select MAX(Date) from newsmessages");
		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function nextMessageId(PDO $dbh)
	{
		$stmt = $dbh->prepare("select MAX(MESSAGE_ID) from newsmessages");
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

	public static function insert(PDO $dbh, array $newsMessage)
	{
		$dbh->beginTransaction();

		$messageId = NewsMessageEntity::nextMessageId($dbh);

		$stmt = $dbh->prepare("insert into newsmessages values (?, ?, ?, ?)");
		if(!$stmt->execute(array($messageId, $newsMessage['Title'], $newsMessage['Date'], $newsMessage['Message'])))
		{
			$dbh->rollBack();
			throw new Exception($stmt->errorInfo()[2]);
		}

		$dbh->commit();

		return $messageId;
	}
	
	public static function update(PDO $dbh, array $newsMessage, $id)
	{
		$stmt = $dbh->prepare("update newsmessages set ".
			"Title = ?, ".
			"Date = ?, ".
			"Message = ? ".
			"where MESSAGE_ID = ?");
		if(!$stmt->execute(array($newsMessage['Title'], $newsMessage['Date'], $newsMessage['Message'], $id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function remove(PDO $dbh, $id)
	{
		$stmt = $dbh->prepare("delete from newsmessages where MESSAGE_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
}
?>
