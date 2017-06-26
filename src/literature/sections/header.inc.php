<p><a href="<?php print($_SERVER["SCRIPT_NAME"]); ?>">Literature</a></p>
<?php
require_once("auth/view/html/displayloginstatus.inc.php");
displayLoginStatus($GLOBALS["authorizationManager"]);
?>
