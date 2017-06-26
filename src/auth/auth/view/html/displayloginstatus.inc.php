<?php
function displayLoginStatus(AuthorizationManager $authorizationManager)
{
	?>
	<div id="login">
		<?php
		if($authorizationManager->authenticated)
		{
			?>
			<p>Logged in as: <?php print($_SESSION["Username"]) ?></p>
			<p><a href="<?php print($_SERVER["SCRIPT_NAME"]); ?>/auth?__operation=logout">Logout</a></p>
			<?php
		}
		else
		{
			?>
			<p>Not logged in</p>
			<p><a href="<?php print($_SERVER["SCRIPT_NAME"]); ?>/auth">Login</a></p>
			<?php
		}
		?>
	</div>
	<?php
}
?>
