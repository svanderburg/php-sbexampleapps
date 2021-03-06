<?php
namespace SBExampleApps\Homework\Model\Objects;
use PDO;
use SBExampleApps\Homework\Model\Entity\QuestionEntity;

class Exam
{
	public $testId;

	public $lastAnsweredQuestionId;

	public $questionCount;

	public $score;

	public $lastAnswer;

	public $currentQuestion;

	public $currentAnswer;

	public $exact;

	public $lastAnswerStatus;

	public $lastProvidedAnswer;

	public $done;

	public function __construct($testId)
	{
		$this->testId = $testId;
		$this->questionCount = 0;
		$this->score = 0;
		$this->currentAnswer = null;
		$this->lastAnsweredQuestionId = 0;
		$this->lastProvidedAnswer = null;
		$this->done = false;
	}

	public function openSuccessiveQuestion(PDO $dbh)
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

	public function checkProvidedAnswer($answer)
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

	public function computeRatio()
	{
		return 100 * $this->score / $this->questionCount;
	}
}
?>
