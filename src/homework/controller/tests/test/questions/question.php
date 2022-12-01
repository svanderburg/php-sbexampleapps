<?php
use SBExampleApps\Homework\Model\CRUD\QuestionCRUDInterface;

global $route, $currentPage, $dbh, $crudInterface;

$crudInterface = new QuestionCRUDInterface($route, $currentPage, $dbh);
$crudInterface->executeOperation();
?>
