<?php
use SBExampleApps\Portal\Model\CRUD\NewsMessageCRUDInterface;

global $route, $currentPage, $dbh, $authorizationManager, $crudInterface;

$crudInterface = new NewsMessageCRUDInterface($route, $currentPage, $dbh, $authorizationManager);
$crudInterface->executeOperation();
?>
