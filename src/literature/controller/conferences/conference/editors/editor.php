<?php
use SBExampleApps\Literature\Model\CRUD\ConferenceEditorCRUDInterface;

global $crudInterface, $dbh, $route, $currentPage, $authorizationManager;

$crudInterface = new ConferenceEditorCRUDInterface($route, $currentPage, $dbh, $authorizationManager);
$crudInterface->executeOperation();
?>
