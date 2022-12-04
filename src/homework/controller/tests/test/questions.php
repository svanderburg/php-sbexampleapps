<?php
use SBData\Model\Form;
use SBData\Model\Table\DBTable;
use SBData\Model\Table\Anchor\AnchorRow;
use SBData\Model\Field\NumericIntKeyLinkField;
use SBData\Model\Field\CheckBoxField;
use SBData\Model\Field\TextField;
use SBExampleApps\Homework\Model\Entity\TestEntity;

global $dbh, $table;

$composeQuestionLink = function (NumericIntKeyLinkField $field, Form $form): string
{
	$questionId = $field->exportValue();
	return $_SERVER["PHP_SELF"]."/".rawurlencode($questionId);
};

$deleteQuestionLink = function (Form $form): string
{
	$questionId = $form->fields['QUESTION_ID']->exportValue();
	return $_SERVER["PHP_SELF"]."/".rawurlencode($questionId)."?__operation=delete_question".AnchorRow::composeRowParameter($form);
};

$table = new DBTable(array(
	"QUESTION_ID" => new NumericIntKeyLinkField("Id", $composeQuestionLink, true),
	"Question" => new TextField("Question", true),
	"Answer" => new TextField("Answer", true),
	"Exact" => new CheckBoxField("Exact")
), array(
	"Delete" => $deleteQuestionLink
));

$table->stmt = TestEntity::queryAllQuestions($dbh, $GLOBALS["query"]["testId"]);
?>
