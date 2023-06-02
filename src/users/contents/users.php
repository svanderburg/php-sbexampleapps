<?php
global $route, $table;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBCrud\View\HTML\displayOperationToolbar($route);
\SBData\View\HTML\displaySemiEditableTable($table);
?>
