<?php
global $crudInterface;

if($_SESSION["exam"]->lastAnswer !== null)
{
	?>
	<p>The previous answer was: <?= $_SESSION["exam"]->lastAnswer ?></p>
	<?php
	if($_SESSION["exam"]->lastProvidedAnswer !== null)
	{
		?>
		<p>You provided: <?= $_SESSION["exam"]->lastProvidedAnswer ?></p>
		<?php
	}

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
		For the exact questions you have answered <?= $_SESSION["exam"]->score ?>
		out of <?= $_SESSION["exam"]->questionCount ?> right, which makes a test score of:
		<?= $_SESSION["exam"]->computeRatio() ?>%
		</p>
		<?php
	}
}
else
{
	?>
	<p><?= $_SESSION["exam"]->currentQuestion ?></p>
	<?php
	\SBData\View\HTML\displayEditableForm($crudInterface->form,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!");
}
?>
