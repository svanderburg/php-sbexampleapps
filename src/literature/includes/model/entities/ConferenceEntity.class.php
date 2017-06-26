<?php
class ConferenceEntity
{
	public static function queryAll(PDO $dbh)
	{
		$stmt = $dbh->prepare("select conferences.CONFERENCE_ID, conferences.Name, conferences.Homepage, publisher.Name as PublisherName, conferences.Location ".
			"from conferences ".
			"inner join publisher on conferences.PUBLISHER_ID = publisher.PUBLISHER_ID ".
			"order by conferences.CONFERENCE_ID");
		if(!$stmt->execute())
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryOne(PDO $dbh, $id)
	{
		$stmt = $dbh->prepare("select * from conferences where CONFERENCE_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryEditors(PDO $dbh, $conferenceId)
	{
		$stmt = $dbh->prepare("select author.AUTHOR_ID, author.LastName, author.FirstName ".
			"from author ".
			"inner join conferences_authors on author.AUTHOR_ID = conferences_authors.AUTHOR_ID ".
			"where conferences_authors.CONFERENCE_ID = ? ".
			"order by author.AUTHOR_ID");
		if(!$stmt->execute(array($conferenceId)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryEditorsSummary(PDO $dbh, $conferenceId)
	{
		$stmt = $dbh->prepare('select concat(author.FirstName, " ", author.LastName) as AuthorName '.
			"from author ".
			"inner join conferences_authors on author.AUTHOR_ID = conferences_authors.AUTHOR_ID ".
			"where conferences_authors.CONFERENCE_ID = ? ".
			"order by AuthorName");
		if(!$stmt->execute(array($conferenceId)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function nextConferenceId(PDO $dbh)
	{
		$stmt = $dbh->prepare("select MAX(CONFERENCE_ID) from conferences");
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


	public static function insert(PDO $dbh, array $conference)
	{
		$dbh->beginTransaction();

		$conferenceId = ConferenceEntity::nextConferenceId($dbh);

		$stmt = $dbh->prepare("insert into conferences values (?, ?, ?, ?, ?)");
		if(!$stmt->execute(array($conferenceId, $conference['Name'], $conference['Homepage'], $conference['PUBLISHER_ID'], $conference['Location'])))
		{
			$dbh->rollBack();
			throw new Exception($stmt->errorInfo()[2]);
		}

		$dbh->commit();

		return $conferenceId;
	}
	
	public static function update(PDO $dbh, array $conference, $id)
	{
		$stmt = $dbh->prepare("update conferences set ".
			"Name = ?, ".
			"Homepage = ?, ".
			"PUBLISHER_ID = ?, ".
			"Location = ? ".
			"where CONFERENCE_ID = ?");
		if(!$stmt->execute(array($conference['Name'], $conference['Homepage'], $conference['PUBLISHER_ID'], $conference['Location'], $id)))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function remove(PDO $dbh, $id)
	{
		$stmt = $dbh->prepare("delete from conferences where CONFERENCE_ID = ?");
		if(!$stmt->execute(array($id)))
			throw new Exception($stmt->errorInfo()[2]);
	}

	public static function insertEditor(PDO $dbh, $conferenceId, $authorId)
	{
		$stmt = $dbh->prepare("insert into conferences_authors values (?, ?)");

		if(!$stmt->execute(array($conferenceId, $authorId)))
			throw new Exception($stmt->errorInfo()[2]);
	}

	public static function removeEditor(PDO $dbh, $conferenceId, $authorId)
	{
		$stmt = $dbh->prepare("delete from conferences_authors where CONFERENCE_ID = ? and AUTHOR_ID = ?");

		if(!$stmt->execute(array($conferenceId, $authorId)))
			throw new Exception($stmt->errorInfo()[2]);
	}
}
?>
