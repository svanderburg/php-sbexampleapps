<?php
use SBExampleApps\Literature\Model\CRUD\ConferenceCRUDInterface;

global $crudInterface, $dbh, $route, $currentPage, $authorizationManager;

$crudInterface = new ConferenceCRUDInterface($route, $currentPage, $dbh, $authorizationManager);
$crudInterface->executeOperation();
?>
