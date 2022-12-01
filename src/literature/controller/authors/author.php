<?php
use SBExampleApps\Literature\Model\CRUD\AuthorCRUDInterface;

global $crudInterface, $dbh, $route, $currentPage, $authorizationManager;

$crudInterface = new AuthorCRUDInterface($route, $currentPage, $dbh, $authorizationManager);
$crudInterface->executeOperation();
?>
