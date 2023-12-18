<?php
namespace framework\packages\SurveyPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\WebshopPackage\entity\Product;

class SurveyQuestion extends DbEntity
{
    const INPUT_TYPE_TEXT = 'text';

    const INPUT_TYPE_TEXTAREA = 'textarea';

    const INPUT_TYPE_SELECT = 'select';

    const INPUT_TYPE_RADIO = 'radio';

    const INPUT_TYPE_CHECKER = 'checker';

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `survey_question` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `survey_id` int(11) DEFAULT NULL,
        `description` text DEFAULT NULL,
        `required` smallint(1) NOT NULL DEFAULT 0,
        `input_type` varchar(20) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=76000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $survey;
    protected $surveyOption = array();
    protected $description;
    protected $required;
    protected $inputType;

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

    public function getSurvey() : ? Survey
    {
        return $this->survey;
    }

    public function addSurveyOption(SurveyOption $surveyOption)
    {
        $this->surveyOption[] = $surveyOption;
    }

    public function setAllSurveyOptions(array $surveyOptions)
    {
        $this->surveyOption = $surveyOptions;
    }

    public function getSurveyOption() : array
    {
        return $this->surveyOption;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setRequired($required)
    {
        if ($required == true || $required == 'true') {
            $required = 1;
        }
        if ($required == false || $required == 'false') {
            $required = 0;
        }
        $this->required = $required;
    }

    public function getRequired() : bool
    {
        $required = false;
        if ((int)$this->required == 1) {
            $required = true;
        }
        if ((int)$this->required == 0) {
            $required = false;
        }

        return $required;
    }

    public function setInputType($inputType)
    {
        $this->inputType = $inputType;
    }

    public function getInputType()
    {
        return $this->inputType;
    }
}
