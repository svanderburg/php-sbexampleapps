<?php
if($GLOBALS["error"] === null)
{
	?>
	<p>
	You are not allowed to view this page!
	</p>
	<?php
}
else
{
	?>
	<?= $GLOBALS["error"] ?>
	<?php
}
