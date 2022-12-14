<p><a href="<?= $_SERVER["SCRIPT_NAME"] ?>">Content Management System</a></p>
<?php
\SBExampleApps\Auth\View\HTML\displayLoginStatus($GLOBALS["authorizationManager"]);
?>
