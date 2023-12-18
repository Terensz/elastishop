<?php
namespace framework\packages\SurveyPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\WebshopPackage\entity\Product;

class SurveyCompletion extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `survey_completion` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `survey_id` int(11) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=78000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $survey;
    protected $surveyCompletionAnswer = [];
    protected $createdAt;

    public function __construct()
    {
        $this->createdAt = $this->getCurrentTimestamp();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSurvey(Survey $survey)
    {
        $this->survey = $survey;
    }

    public function getSurvey()
    {
        return $this->survey;
    }

    public function addSurveyCompletionAnswer(SurveyCompletionAnswer $surveyCompletionAnswer)
    {
        $this->surveyCompletionAnswer[] = $surveyCompletionAnswer;
    }

    public function getSurveyCompletionAnswer()
    {
        return $this->surveyCompletionAnswer;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
