<?php
global $dbh, $currentPage, $checker;

if(\SBPageManager\View\HTML\visitedPageManagerPage($currentPage))
	\SBPageManager\View\HTML\displayDynamicMenuSection($dbh, 0, $currentPage);
?>
