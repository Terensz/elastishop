<?php
namespace framework\packages\SurveyPackage\entity;

use App;
use framework\component\parent\DbEntity;
use framework\kernel\utility\BasicUtils;
use framework\packages\SurveyPackage\service\SurveyService;

class Survey extends DbEntity
{
    const MAX_ANSWER_VARIANTS_DISPLAYED_ON_CHART = 25;
    
    const STATUS_INACTIVE = 0; // Not visible

    const STATUS_ACTIVE = 1; // RW

    const STATUS_CLOSED = 2; // R

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `survey` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `title` varchar(250) DEFAULT NULL,
        `slug` varchar(250) DEFAULT NULL,
        `description` text DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` smallint(2) DEFAULT 0,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=75000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $website;
    protected $surveyQuestion = array();
    protected $title;
    protected $slug;
    protected $description;
    protected $createdAt;
    protected $status;

    public function __construct()
    {
        $this->website = App::getWebsite();
        $this->createdAt = $this->getCurrentTimestamp();
        $this->status = self::STATUS_INACTIVE;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function checkCorrectWebsite() 
    {
        return App::getWebsite() == $this->website ? true : false;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function addSurveyQuestion(SurveyQuestion $surveyQuestion)
    {
        $this->surveyQuestion[] = $surveyQuestion;
    }

    public function setAllSurveyQuestions(array $surveyQuestions)
    {
        $this->surveyQuestion = $surveyQuestions;
    }

    public function getSurveyQuestion() : array
    {
        return $this->surveyQuestion;
    }

    public function setTitle($title)
    {
        $this->slug = BasicUtils::slugify($title);
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setSlug($slug)
    {
        if (empty($slug) && $this->slug) {
            return null;
        }
        App::getContainer()->wireService('SurveyPackage/service/SurveyService');
        $surveyService = new SurveyService();
        $slug = $surveyService->createUniqueSlug($slug, $this->id);
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
