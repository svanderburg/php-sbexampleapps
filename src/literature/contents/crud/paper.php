<?php
global $crudModel, $authorizationManager;
?>
<p>
	<?php
	if(array_key_exists("paperId", $GLOBALS["query"]))
	{
		$conferencesURL = $_SERVER["SCRIPT_NAME"]."/conferences/".$GLOBALS["query"]["conferenceId"];
		?>
		<a href="<?php print($conferencesURL); ?>">&laquo; Conference: <?php print($GLOBALS["query"]["conferenceId"]); ?></a>
		<?php
		if($authorizationManager->authenticated)
		{
			?>
			| <a href="<?php print($conferencesURL); ?>?__operation=create_paper">Add paper</a>
			<?php
		}
		?>
		| <a href="<?php print($_SERVER["PHP_SELF"]); ?>/reference">View reference citation</a>
		<?php
	}
	?>
</p>
<?php
/* Display paper form */
if($authorizationManager->authenticated)
{
	\SBData\View\HTML\displayEditableForm($crudModel->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}
else
	\SBData\View\HTML\displayForm($crudModel->form);

/* Display optional PDF link */
if($crudModel->hasPDF)
{
	?>
	<p>
	<a href="<?php print(dirname($_SERVER["SCRIPT_NAME"])); ?>/pdf/<?php print($crudModel->keyFields["conferenceId"]->value); ?>/<?php print($crudModel->keyFields["paperId"]->value); ?>.pdf">View PDF</a>
	<?php
	if($authorizationManager->authenticated)
	{
		?>
		<a href="<?php print($_SERVER["PHP_SELF"]); ?>?__operation=delete_paper_pdf">Delete PDF</a>
		<?php
	}
	?>
	</p>
	<?php
}

/* Display authors section */
if($crudModel->addAuthorForm !== null || $crudModel->authorsTable !== null)
{
	?>
	<h2>Authors</h2>
	<?php
}

if($authorizationManager->authenticated && $crudModel->addAuthorForm !== null)
{
	\SBData\View\HTML\displayEditableForm($crudModel->addAuthorForm,
		"Add author",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}

if($crudModel->authorsTable !== null)
{
	if($authorizationManager->authenticated)
		\SBData\View\HTML\displaySemiEditableTable($crudModel->authorsTable);
	else
		\SBData\View\HTML\displayTable($crudModel->authorsTable);
}
?>
