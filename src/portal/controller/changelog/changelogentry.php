<?php
use SBExampleApps\Portal\Model\CRUD\ChangeLogEntryCRUDInterface;

global $route, $currentPage, $dbh, $authorizationManager, $crudInterface;

$crudInterface = new ChangeLogEntryCRUDInterface($route, $currentPage, $dbh, $authorizationManager);
$crudInterface->executeOperation();
?>
