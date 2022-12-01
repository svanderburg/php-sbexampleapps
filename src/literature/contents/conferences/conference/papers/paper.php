<?php
global $crudInterface, $authorizationManager;
?>
<p>
	<?php
	if(array_key_exists("paperId", $GLOBALS["query"]))
	{
		$conferencesURL = $_SERVER["SCRIPT_NAME"]."/conferences/".$GLOBALS["query"]["conferenceId"];
		?>
		<a href="<?= $conferencesURL ?>">&laquo; Conference: <?= $GLOBALS["query"]["conferenceId"] ?></a>
		<?php
		if($authorizationManager->authenticated)
		{
			?>
			| <a href="<?= $conferencesURL ?>?__operation=create_paper">Add paper</a>
			<?php
		}
		?>
		| <a href="<?= $_SERVER["PHP_SELF"] ?>/reference">View reference citation</a>
		<?php
	}
	?>
</p>
<?php
/* Display paper form */
if($authorizationManager->authenticated)
{
	\SBData\View\HTML\displayEditableForm($crudInterface->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}
else
	\SBData\View\HTML\displayForm($crudInterface->form);

/* Display optional PDF link */
if($crudInterface->hasPDF)
{
	?>
	<p>
	<a href="<?php print(dirname($_SERVER["SCRIPT_NAME"])); ?>/pdf/<?= $GLOBALS["query"]["conferenceId"] ?>/<?= $GLOBALS["query"]["paperId"] ?>.pdf">View PDF</a>
	<?php
	if($authorizationManager->authenticated)
	{
		?>
		<a href="<?= $_SERVER["PHP_SELF"] ?>?__operation=delete_paper_pdf">Delete PDF</a>
		<?php
	}
	?>
	</p>
	<?php
}

/* Display authors section */
if($crudInterface->addAuthorForm !== null || $crudInterface->authorsTable !== null)
{
	?>
	<a name="authors"></a>
	<h2>Authors</h2>
	<?php
}

if($authorizationManager->authenticated && $crudInterface->addAuthorForm !== null)
{
	\SBData\View\HTML\displayEditableForm($crudInterface->addAuthorForm,
		"Add author",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}

if($crudInterface->authorsTable !== null)
{
	if($authorizationManager->authenticated)
		\SBData\View\HTML\displaySemiEditableTable($crudInterface->authorsTable, "No authors", "author-row");
	else
		\SBData\View\HTML\displayTable($crudInterface->authorsTable, "No authors");
}
?>
