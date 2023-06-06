<?php
global $route, $crudInterface;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);
\SBCrud\View\HTML\displayOperationToolbar($route, 2);
\SBData\View\HTML\displayEditableForm($crudInterface->form);
?>
