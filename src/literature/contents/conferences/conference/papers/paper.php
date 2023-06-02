<?php
use SBCrud\Model\RouteUtils;

global $route, $crudInterface, $authorizationManager;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 4);
?>
<div class="tabpage">
	<?php
	/* Display paper form */
	if($authorizationManager->authenticated)
	{
		\SBCrud\View\HTML\displayOperationToolbar($route, 2);
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
		<a href="<?= dirname($_SERVER["SCRIPT_NAME"]) ?>/pdf/<?= $GLOBALS["query"]["conferenceId"] ?>/<?= $GLOBALS["query"]["paperId"] ?>.pdf">View PDF</a>
		<?php
		if($authorizationManager->authenticated)
		{
			?>
			<a href="<?= RouteUtils::composeSelfURL() ?>?__operation=delete_paper_pdf">Delete PDF</a>
			<?php
		}
		?>
		</p>
		<?php
	}
	?>
</div>
