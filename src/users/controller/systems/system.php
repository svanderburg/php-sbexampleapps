<?php
use SBExampleApps\Users\Model\CRUD\SystemCRUDInterface;

global $route, $currentPage, $dbh, $crudInterface;

$crudInterface = new SystemCRUDInterface($route, $currentPage, $dbh);
$crudInterface->executeOperation();
?>
