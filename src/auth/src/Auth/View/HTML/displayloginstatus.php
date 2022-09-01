<?php
namespace SBExampleApps\Auth\View\HTML;
use SBExampleApps\Auth\Model\AuthorizationManager;

function displayLoginStatus(AuthorizationManager $authorizationManager): void
{
	?>
	<div id="login">
		<?php
		if($authorizationManager->authenticated)
		{
			?>
			Logged in as: <?php print($_SESSION["Username"]) ?><br>
			<a href="<?php print($_SERVER["SCRIPT_NAME"]); ?>/auth?__operation=logout">Logout</a>
			<?php
		}
		else
		{
			?>
			Not logged in<br>
			<a href="<?php print($_SERVER["SCRIPT_NAME"]); ?>/auth">Login</a>
			<?php
		}
		?>
	</div>
	<?php
}
?>
