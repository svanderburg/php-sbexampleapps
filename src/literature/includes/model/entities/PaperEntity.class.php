<?php
class PaperEntity
{
	public static function queryAll(PDO $dbh, $conferenceId)
	{
		$stmt = $dbh->prepare("select PAPER_ID, Title, Date, URL, Comment from paper where CONFERENCE_ID = ? order by PAPER_ID");
		if(!$stmt->execute(array($conferenceId)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryOne(PDO $dbh, $paperId, $conferenceId)
	{
		$stmt = $dbh->prepare("select * from paper where PAPER_ID = ? and CONFERENCE_ID = ?");
		if(!$stmt->execute(array($paperId, $conferenceId)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function searchByKeyword(PDO $dbh, $keyword)
	{
		$stmt = $dbh->prepare("select distinct paper.PAPER_ID, paper.Title, paper.Date, paper.URL, paper.Comment, conferences.CONFERENCE_ID, conferences.Name as ConferenceName, publisher.PUBLISHER_ID, publisher.Name as PublisherName ".
			"from paper ".
			"left outer join conferences on paper.CONFERENCE_ID = conferences.CONFERENCE_ID ".
			"left outer join paper_author on paper.PAPER_ID = paper_author.PAPER_ID ".
			"left outer join author on paper_author.AUTHOR_ID = author.AUTHOR_ID ".
			"left outer join publisher on conferences.PUBLISHER_ID = publisher.PUBLISHER_ID ".
			"where paper.Title like ? ".
			"or paper.Date like ? ".
			"or paper.URL like ? ".
			"or paper.Abstract like ? ".
			"or conferences.Name like ? ".
			"or conferences.Homepage like ? ".
			"or conferences.Location like ? ".
			"or author.LastName like ? ".
			"or author.FirstName like ? ".
			"or author.Homepage like ? ".
			"or publisher.Name like ? ".
			"order by paper.PAPER_ID");
		$keywordStr = "%".$keyword."%";
		if(!$stmt->execute(array($keywordStr, $keywordStr, $keywordStr, $keywordStr, $keywordStr, $keywordStr, $keywordStr, $keywordStr, $keywordStr, $keywordStr, $keywordStr)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryOneForReference(PDO $dbh, $paperId, $conferenceId)
	{
		$stmt = $dbh->prepare("select paper.PAPER_ID, paper.Title as PaperTitle, paper.Date as PaperDate, paper.Abstract as PaperAbstract, paper.URL as PaperURL, paper.Comment as PaperComment, conferences.Name as ConferenceName, conferences.Homepage as ConferenceHomepage, conferences.Location as ConferenceLocation, publisher.Name as PublisherName ".
			"from paper ".
			"inner join conferences on paper.CONFERENCE_ID = conferences.CONFERENCE_ID ".
			"inner join publisher on conferences.PUBLISHER_ID = publisher.PUBLISHER_ID ".
			"where paper.PAPER_ID = ? and paper.CONFERENCE_ID = ?");
		if(!$stmt->execute(array($paperId, $conferenceId)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryAuthors(PDO $dbh, $paperId, $conferenceId)
	{
		$stmt = $dbh->prepare("select author.AUTHOR_ID, author.LastName, author.FirstName ".
			"from author ".
			"inner join paper_author on author.AUTHOR_ID = paper_author.AUTHOR_ID ".
			"where paper_author.PAPER_ID = ? and paper_author.CONFERENCE_ID = ? ".
			"order by author.AUTHOR_ID");
		if(!$stmt->execute(array($paperId, $conferenceId)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function queryAuthorsSummary(PDO $dbh, $paperId, $conferenceId)
	{
		$stmt = $dbh->prepare('select concat(author.FirstName, " ", author.LastName) as AuthorName, author.Homepage '.
			"from paper ".
			"inner join paper_author on paper.PAPER_ID = paper_author.PAPER_ID and paper.CONFERENCE_ID = paper_author.CONFERENCE_ID ".
			"inner join author on paper_author.AUTHOR_ID = author.AUTHOR_ID ".
			"where paper.PAPER_ID = ? and paper.CONFERENCE_ID = ? ".
			"order by AuthorName");

		if(!$stmt->execute(array($paperId, $conferenceId)))
			throw new Exception($stmt->errorInfo()[2]);

		return $stmt;
	}

	public static function nextPaperId(PDO $dbh, $conferenceId)
	{
		$stmt = $dbh->prepare("select MAX(PAPER_ID) from paper where CONFERENCE_ID = ?");
		if(!$stmt->execute(array($conferenceId)))
		{
			throw new Exception($stmt->errorInfo()[2]);
			$dbh->rollBack();
		}
		
		if(($row = $stmt->fetch()) === false)
			return 1;
		else
			return $row[0] + 1;
	}

	public static function insert(PDO $dbh, array $paper, $conferenceId)
	{
		$dbh->beginTransaction();

		$paperId = PaperEntity::nextPaperId($dbh, $conferenceId);

		$stmt = $dbh->prepare("insert into paper values (?, ?, ?, ?, ?, ?, ?, ?)");
		if(!$stmt->execute(array($paperId, $conferenceId, $paper['Title'], $paper['Date'], $paper['Abstract'], $paper['URL'], $paper['Comment'], $paper['hasPDF'])))
		{
			$dbh->rollBack();
			throw new Exception($stmt->errorInfo()[2]);
		}

		$dbh->commit();

		return $paperId;
	}
	
	public static function update(PDO $dbh, array $paper, $paperId, $conferenceId)
	{
		$stmt = $dbh->prepare("update paper set ".
			"Title = ?, ".
			"Date = ?, ".
			"Abstract = ?, ".
			"URL = ?, ".
			"Comment = ?, ".
			"hasPDF = ? ".
			"where PAPER_ID = ? and CONFERENCE_ID = ?");
		if(!$stmt->execute(array($paper['Title'], $paper['Date'], $paper['Abstract'], $paper['URL'], $paper['Comment'], $paper['hasPDF'], $paperId, $conferenceId)))
			throw new Exception($stmt->errorInfo()[2]);
	}
	
	public static function remove(PDO $dbh, $paperId, $conferenceId)
	{
		$stmt = $dbh->prepare("delete from paper where PAPER_ID = ? and CONFERENCE_ID = ?");
		if(!$stmt->execute(array($paperId, $conferenceId)))
			throw new Exception($stmt->errorInfo()[2]);
	}

	public static function removePDF(PDO $dbh, $paperId, $conferenceId)
	{
		$stmt = $dbh->prepare("update paper set ".
			"hasPDF = 0 ".
			"where PAPER_ID = ? and CONFERENCE_ID = ?");
		if(!$stmt->execute(array($paperId, $conferenceId)))
			throw new Exception($stmt->errorInfo()[2]);
	}

	public static function insertAuthor(PDO $dbh, $paperId, $conferenceId, $authorId)
	{
		$stmt = $dbh->prepare("insert into paper_author values (?, ?, ?)");

		if(!$stmt->execute(array($paperId, $conferenceId, $authorId)))
			throw new Exception($stmt->errorInfo()[2]);
	}

	public static function removeAuthor(PDO $dbh, $paperId, $conferenceId, $authorId)
	{
		$stmt = $dbh->prepare("delete from paper_author where PAPER_ID = ? and CONFERENCE_ID = ? and AUTHOR_ID = ?");

		if(!$stmt->execute(array($paperId, $conferenceId, $authorId)))
			throw new Exception($stmt->errorInfo()[2]);
	}
}
?>
