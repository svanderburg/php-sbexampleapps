<?php
global $route, $crudInterface, $authorizationManager;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);

$newsURL = $_SERVER["SCRIPT_NAME"]."/news";

if($authorizationManager->authenticated)
{
	\SBCrud\View\HTML\displayOperationToolbar($route);

	\SBData\View\HTML\displayEditableForm($crudInterface->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
	?>
	<script type="text/javascript">
	sbeditor.initEditors();
	</script>
	<?php
}
else
{
	$newsMessage = $crudInterface->form->exportValues();
	\SBExampleApps\Portal\View\displayNewsMessage($newsMessage, $authorizationManager);
}
?>
