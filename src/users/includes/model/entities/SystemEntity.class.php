<?php
class SystemEntity
{
	public static function queryAll(PDO $dbh)
	{
		$stmt = $dbh->prepare("select * from system order by SYSTEM_ID");
		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryOne(PDO $dbh, $id)
	{
		$stmt = $dbh->prepare("select * from system where SYSTEM_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function insert(PDO $dbh, array $system)
	{
		$stmt = $dbh->prepare("insert into system values (?, ?)");
		if(!$stmt->execute(array($system['SYSTEM_ID'], $system['Description'])))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function update(PDO $dbh, array $system, $id)
	{
		$stmt = $dbh->prepare("update system set ".
			"SYSTEM_ID = ?, ".
			"Description = ? ".
			"where SYSTEM_ID = ?");
		if(!$stmt->execute(array($system['SYSTEM_ID'], $system['Description'], $id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function remove(PDO $dbh, $id)
	{
		$stmt = $dbh->prepare("delete from system where SYSTEM_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
}
?>
