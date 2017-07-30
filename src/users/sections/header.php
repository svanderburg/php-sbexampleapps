<p><a href="<?php print($_SERVER["SCRIPT_NAME"]); ?>">User management</a></p>
<?php
\SBExampleApps\Auth\View\HTML\displayLoginStatus($GLOBALS["authorizationManager"]);
?>
