<?php
\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBLayout\View\HTML\displayEmbeddedMenuSection($route, 2);
?>
<div class="tabpage">
	<?php
	$testsURL = $_SERVER["SCRIPT_NAME"]."/tests";

	if(array_key_exists("query", $GLOBALS) && array_key_exists("testId", $GLOBALS["query"]))
	{
		?>
		<p>
			<a href="?__operation=create_test">Add test</a>
		</p>
		<?php
	}

	global $crudInterface;

	\SBData\View\HTML\displayEditableForm($crudInterface->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
	?>
</div>
