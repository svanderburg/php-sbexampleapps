<?php
use SBExampleApps\Homework\Model\CRUD\ExamCRUDInterface;

global $currentPage, $dbh, $crudInterface;

$crudInterface = new ExamCRUDInterface($currentPage, $dbh);
$crudInterface->executeOperation();
?>
