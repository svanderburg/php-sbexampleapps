<p><a href="<?php print($_SERVER["SCRIPT_NAME"]); ?>">Content Management System</a></p>
<?php
\SBExampleApps\Auth\View\HTML\displayLoginStatus($GLOBALS["authorizationManager"]);
?>
