<?php
namespace SBExampleApps\Homework\Model\Objects;
use PDO;
use SBExampleApps\Homework\Model\Entity\QuestionEntity;

class Exam
{
	public string $testId;

	public int $lastAnsweredQuestionId;

	public int $questionCount;

	public int $score;

	public ?string $lastAnswer;

	public string $currentQuestion;

	public ?string $currentAnswer;

	public bool $exact;

	public ?bool $lastAnswerStatus;

	public ?string $lastProvidedAnswer;

	public bool $done;

	public function __construct(string $testId)
	{
		$this->testId = $testId;
		$this->questionCount = 0;
		$this->score = 0;
		$this->currentAnswer = null;
		$this->lastAnsweredQuestionId = 0;
		$this->lastProvidedAnswer = null;
		$this->done = false;
	}

	public function openSuccessiveQuestion(PDO $dbh): bool|array
	{
		$this->lastAnswer = $this->currentAnswer;

		$stmt = QuestionEntity::querySuccessiveQuestion($dbh, $this->testId, $this->lastAnsweredQuestionId);

		if(($row = $stmt->fetch()) === false)
		{
			$this->done = true;
			return false;
		}
		else
		{
			$this->lastAnsweredQuestionId = $row["QUESTION_ID"];
			$this->currentQuestion = $row["Question"];
			$this->currentAnswer = $row["Answer"];
			$this->exact = $row["Exact"];
			return $row;
		}
	}

	public function checkProvidedAnswer(string $answer): void
	{
		if($this->exact)
		{
			if($answer === $this->currentAnswer)
			{
				$this->score++;
				$this->lastAnswerStatus = true;
			}
			else
				$this->lastAnswerStatus = false;

			$this->questionCount++;
		}
		else
			$this->lastAnswerStatus = null;

		$this->lastProvidedAnswer = $answer;
	}

	public function computeRatio(): float
	{
		return 100 * $this->score / $this->questionCount;
	}
}
?>
