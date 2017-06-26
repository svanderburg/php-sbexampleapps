<?php
require_once("model/entities/QuestionEntity.class.php");

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

	public $done;

	public function __construct($testId)
	{
		$this->testId = $testId;
		$this->questionCount = 0;
		$this->score = 0;
		$this->currentAnswer = null;
		$this->lastAnsweredQuestionId = 0;
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
	}

	public function computeRatio()
	{
		return 100 * $this->score / $this->questionCount;
	}
}
?>
