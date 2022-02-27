<?php
namespace SBExampleApps\Portal\View;
use SBExampleApps\Auth\Model\AuthorizationManager;

function displayNewsMessage(array $newsMessage, AuthorizationManager $authorizationManager): void
{
	?>
	<table style="width: 100%;">
		<tr>
			<th style="text-align: left;">
				<a href="<?php print($_SERVER["SCRIPT_NAME"]."/news/".$newsMessage["MESSAGE_ID"]); ?>"><?php print($newsMessage["Title"]); ?></a>
				<span style="float: right;">
					<?php
					print($newsMessage["Date"]);
					if($authorizationManager->authenticated)
					{
						?>
						<a href="<?php print($_SERVER["SCRIPT_NAME"]."/news/".$newsMessage["MESSAGE_ID"]); ?>?__operation=remove_newsmessage">Delete</a>
						<?php
					}
					?>
				</span>
			</th>
		</tr>
		<tr>
			<td><?php print($newsMessage["Message"]); ?></td>
		</tr>
	</table>
	<?php
}
?>
