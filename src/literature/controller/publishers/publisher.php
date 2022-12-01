<?php
use SBExampleApps\Literature\Model\CRUD\PublisherCRUDInterface;

global $crudInterface, $dbh, $route, $currentPage, $authorizationManager;

$crudInterface = new PublisherCRUDInterface($route, $currentPage, $dbh, $authorizationManager);
$crudInterface->executeOperation();
?>
