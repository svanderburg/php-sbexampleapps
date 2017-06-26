<?php
class PublisherEntity
{
	public static function queryAll(PDO $dbh)
	{
		$stmt = $dbh->prepare("select * from publisher order by PUBLISHER_ID");
		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryOne(PDO $dbh, $id)
	{
		$stmt = $dbh->prepare("select * from publisher where PUBLISHER_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function querySummary(PDO $dbh)
	{
		$stmt = $dbh->prepare("select PUBLISHER_ID, Name from publisher order by Name");
		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function insert(PDO $dbh, array $publisher)
	{
		$stmt = $dbh->prepare("insert into publisher values (?, ?)");
		if(!$stmt->execute(array($publisher['PUBLISHER_ID'], $publisher['Name'])))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function update(PDO $dbh, array $publisher, $id)
	{
		$stmt = $dbh->prepare("update publisher set ".
			"PUBLISHER_ID = ?, ".
			"Name = ? ".
			"where PUBLISHER_ID = ?");
		if(!$stmt->execute(array($publisher['PUBLISHER_ID'], $publisher['Name'], $id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function remove(PDO $dbh, $id)
	{
		$stmt = $dbh->prepare("delete from publisher where PUBLISHER_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
}
?>
