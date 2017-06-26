<?php
require_once("model/entities/PaperEntity.class.php");
require_once("model/entities/ConferenceEntity.class.php");
require_once("data/model/field/NumericIntTextField.class.php");
require_once("biblio/model/InProceedings.class.php");
require_once("biblio/model/Book.class.php");
require_once("biblio/model/Author.class.php");
require_once("biblio/view/html/displaybib.inc.php");

global $dbh;

function generateMonth($date)
{
	$monthNumber = $date->format('m');
	switch($monthNumber)
	{
		case "01":
			return "January";
		case "02":
			return "February";
		case "03":
			return "March";
		case "04":
			return "April";
		case "05":
			return "May";
		case "06":
			return "June";
		case "07":
			return "July";
		case "08":
			return "August";
		case "09":
			return "September";
		case "10":
			return "October";
		case "11":
			return "November";
		case "12":
			return "December";
		default:
			throw new Exception("Unknown month number: ".$monthNumber);
	}
}

$paperIdField = new NumericIntTextField("PAPER_ID", true);
$paperIdField->value = $GLOBALS["query"]["paperId"];
$conferenceIdField = new NumericIntTextField("CONFERENCE_ID", true);
$conferenceIdField->value = $GLOBALS["query"]["conferenceId"];

if(!$paperIdField->checkField("PAPER_ID") || !$conferenceIdField->checkField("CONFERENCE_ID"))
{
	?>
	<p>The keys are invalid!</p>
	<?php
}
else
{
	$stmt = PaperEntity::queryOneForReference($dbh, $paperIdField->value, $conferenceIdField->value);

	if(($paper = $stmt->fetch()) === false)
	{
		?>
		<p>Requested publication does not exists!</p>
		<?php
	}
	else
	{
		/* Process authors */
		$authors = array();
		$stmt = PaperEntity::queryAuthorsSummary($dbh, $paperIdField->value, $conferenceIdField->value);

		while(($author = $stmt->fetch()) !== false)
			array_push($authors, new Author($author["AuthorName"], $author["Homepage"]));
		
		if(count($authors) == 0)
			$authors = null;

		/* Process editors */
		$editors = null;

		$stmt = ConferenceEntity::queryEditorsSummary($dbh, $GLOBALS["query"]["conferenceId"]);

		while(($editor = $stmt->fetch()) !== false)
		{
			if($editors === null)
				$editors = $editor["AuthorName"];
			else
				$editors .= " and ".$editor["AuthorName"];
		}

		/* Parse date */
		$date = DateTime::createFromFormat("Y-m-d", $paper["PaperDate"]);

		/* Compose and display publication */

		$publication = new InProceedings($paper["PAPER_ID"],
			$authors,
			$paper["PaperTitle"],
			new Book($paper["ConferenceName"], $paper["ConferenceHomepage"] == "" ? null : $paper["ConferenceHomepage"],
				$paper["PublisherName"], $editors, $paper["ConferenceLocation"]),
			generateMonth($date), $date->format('Y'),
			$paper["PaperAbstract"],
			$paper["PaperURL"],
			$paper["PaperComment"] == "" ? null : $paper["PaperComment"]);

		displayPublication($publication, dirname($_SERVER["PHP_SELF"])."/pdf");
	}
}
?>
