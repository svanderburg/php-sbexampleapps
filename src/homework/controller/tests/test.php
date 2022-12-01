<?php
use SBExampleApps\Homework\Model\CRUD\TestCRUDInterface;

global $route, $currentPage, $dbh, $crudInterface;

$crudInterface = new TestCRUDInterface($route, $currentPage, $dbh);
$crudInterface->executeOperation();
?>
