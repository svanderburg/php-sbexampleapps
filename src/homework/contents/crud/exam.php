<?php
global $crudModel;

if($_SESSION["exam"]->lastAnswer !== null)
{
	?>
	<p>The previous answer was: <?php print($_SESSION["exam"]->lastAnswer); ?></p>
	<?php
	if($_SESSION["exam"]->lastAnswerStatus !== null)
	{
		if($_SESSION["exam"]->lastAnswerStatus)
		{
			?>
			<p class="correct">The provided answer was correct!</p>
			<?php
		}
		else
		{
			?>
			<p class="incorrect">The provided answer was incorrect!</p>
			<?php
		}
	}
}

if($_SESSION["exam"]->done)
{
	?>
	<p>The test is done!</p>
	<?php
	if($_SESSION["exam"]->questionCount > 0)
	{
		?>
		<p>
		For the exact questions you have answered <?php print($_SESSION["exam"]->score); ?>
		out of <?php print($_SESSION["exam"]->questionCount); ?> right, which makes a test score of:
		<?php print($_SESSION["exam"]->computeRatio()); ?>%
		</p>
		<?php
	}
}
else
{
	?>
	<p><?php print($_SESSION["exam"]->currentQuestion); ?></p>
	<?php
	\SBData\View\HTML\displayEditableForm($crudModel->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}
?>
