<?php
require_once("data/view/html/form.inc.php");
require_once("data/view/html/table.inc.php");

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
	displayEditableForm($crudModel->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}
else
	displayForm($crudModel->form);

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
	displayEditableForm($crudModel->addAuthorForm,
		"Add author",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}

if($crudModel->authorsTable !== null)
{
	if($authorizationManager->authenticated)
	{
		function deletePaperAuthorLink(Form $form)
		{
			return $_SERVER['PHP_SELF']."?__operation=delete_paper_author&amp;AUTHOR_ID=".$form->fields["AUTHOR_ID"]->value;
		}

		displayTable($crudModel->authorsTable, "deletePaperAuthorLink");
	}
	else
		displayTable($crudModel->authorsTable);
}
?>
