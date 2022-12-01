<?php
use SBExampleApps\Portal\Model\Entity\NewsMessageEntity;

global $dbh, $stmt;
$stmt = NewsMessageEntity::queryAll($dbh, (int)$GLOBALS["requestParameters"]["page"]);
?>
