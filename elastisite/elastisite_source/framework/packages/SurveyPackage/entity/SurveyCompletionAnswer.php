<?php
namespace framework\packages\SurveyPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\WebshopPackage\entity\Product;

class SurveyCompletionAnswer extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `survey_completion_answer` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `survey_completion_id` int(11) DEFAULT NULL,
        `survey_question_id` int(11) DEFAULT NULL,
        `answer_value` varchar(250) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=79000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $surveyCompletion;
    protected $surveyQuestion;
    protected $answerValue;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSurveyCompletion(SurveyCompletion $surveyCompletion)
    {
        $this->surveyCompletion = $surveyCompletion;
    }

    public function getSurveyCompletion() : ? SurveyCompletion
    {
        return $this->surveyCompletion;
    }

    public function setSurveyQuestion(SurveyQuestion $surveyQuestion)
    {
        $this->surveyQuestion = $surveyQuestion;
    }

    public function getSurveyQuestion() : ? SurveyQuestion
    {
        return $this->surveyQuestion;
    }

    public function setAnswerValue($answerValue)
    {
        $this->answerValue = $answerValue;
    }

    public function getAnswerValue()
    {
        return $this->answerValue;
    }
}
