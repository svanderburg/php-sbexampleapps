<?php
global $route, $crudInterface, $authorizationManager;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);

if($authorizationManager->authenticated)
{
	\SBCrud\View\HTML\displayOperationToolbar($route);
	\SBData\View\HTML\displayEditableForm($crudInterface->form);
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
