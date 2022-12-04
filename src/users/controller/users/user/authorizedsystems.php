<?php
use SBExampleApps\Users\Model\CRUD\AuthorizedSystemsCRUDInterface;

global $route, $currentPage, $dbh, $crudInterface;

$crudInterface = new AuthorizedSystemsCRUDInterface($route, $currentPage, $dbh);
$crudInterface->executeOperation();
?>
