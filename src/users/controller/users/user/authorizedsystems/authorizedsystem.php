<?php
use SBExampleApps\Users\Model\CRUD\AuthorizedSystemCRUDInterface;

global $route, $currentPage, $dbh, $crudInterface;

$crudInterface = new AuthorizedSystemCRUDInterface($route, $currentPage, $dbh);
$crudInterface->executeOperation();
?>
