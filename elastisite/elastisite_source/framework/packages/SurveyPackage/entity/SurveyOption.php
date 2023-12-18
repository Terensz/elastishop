<?php
namespace framework\packages\SurveyPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\WebshopPackage\entity\Product;

class SurveyOption extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `survey_option` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `survey_question_id` int(11) DEFAULT NULL,
        `description` varchar(250) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=77000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $surveyQuestion;
    protected $description;
    // protected $optionKey;
    // protected $optionValue;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSurveyQuestion(SurveyQuestion $surveyQuestion)
    {
        $this->surveyQuestion = $surveyQuestion;
    }

    public function getSurveyQuestion() : ? SurveyQuestion
    {
        return $this->surveyQuestion;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    // public function setOptionKey($optionKey)
    // {
    //     $this->optionKey = $optionKey;
    // }

    // public function getOptionKey()
    // {
    //     return $this->optionKey;
    // }

    // public function setOptionValue($optionValue)
    // {
    //     $this->optionValue = $optionValue;
    // }

    // public function getOptionValue()
    // {
    //     return $this->optionValue;
    // }
}
