<?php
use SBExampleApps\Literature\Model\CRUD\PaperCRUDInterface;

global $crudInterface, $dbh, $route, $currentPage, $authorizationManager;

$crudInterface = new PaperCRUDInterface($route, $currentPage, $dbh, $authorizationManager);
$crudInterface->executeOperation();
?>
