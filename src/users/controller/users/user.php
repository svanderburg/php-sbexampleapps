<?php
use SBExampleApps\Users\Model\CRUD\UserCRUDInterface;

global $route, $currentPage, $dbh, $crudInterface;

$crudInterface = new UserCRUDInterface($route, $currentPage, $dbh);
$crudInterface->executeOperation();
?>
