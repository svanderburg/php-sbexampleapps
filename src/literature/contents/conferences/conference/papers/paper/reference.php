<?php
use SBBiblio\Model\Author;
use SBBiblio\Model\Book;
use SBBiblio\Model\InProceedings;
use SBExampleApps\Literature\Model\Entity\ConferenceEntity;
use SBExampleApps\Literature\Model\Entity\PaperEntity;

global $route, $dbh;

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

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 4);
?>
<div class="tabpage">
	<?php
	$stmt = PaperEntity::queryOneForReference($dbh, $GLOBALS["query"]["paperId"], $GLOBALS["query"]["conferenceId"]);

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
		$stmt = PaperEntity::queryAuthorsSummary($dbh, $GLOBALS["query"]["paperId"], $GLOBALS["query"]["conferenceId"]);

		while(($author = $stmt->fetch()) !== false)
			array_push($authors, new Author($author["AuthorName"], $author["Homepage"]));

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
			$paper["PaperURL"] == "" ? null : $paper["PaperURL"],
			$paper["PaperComment"] == "" ? null : $paper["PaperComment"]);

		\SBBiblio\View\HTML\displayPublication($publication, dirname($_SERVER["PHP_SELF"])."/pdf");
	}
	?>
</div>
