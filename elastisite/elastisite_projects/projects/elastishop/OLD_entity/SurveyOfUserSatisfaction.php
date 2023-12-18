<?php
namespace projects\Meheszellato\entity;

use framework\component\parent\DbEntity;

class SurveyOfUserSatisfaction extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `survey_of_user_satisfaction` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        -- `visitor_code` varchar(20) DEFAULT NULL,
        `answer1` varchar(200) DEFAULT NULL,
        `answer2` varchar(200) DEFAULT NULL,
        `answer3` varchar(200) DEFAULT NULL,
        `answer4` text DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=39000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    private $id;
    // private $visitorCode;
    private $answer1;
    private $answer2;
    private $answer3;
    private $answer4;
    private $createdAt;

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

    // public function setVisitorCode($visitorCode)
    // {
    //     $this->visitorCode = $visitorCode;
    // }

    // public function getVisitorCode()
    // {
    //     return $this->visitorCode;
    // }

    public function setAnswer1($answer1)
    {
        $this->answer1 = $answer1;
    }

    public function getAnswer1()
    {
        return $this->answer1;
    }

    public function setAnswer2($answer2)
    {
        $this->answer2 = $answer2;
    }

    public function getAnswer2()
    {
        return $this->answer2;
    }

    public function setAnswer3($answer3)
    {
        $this->answer3 = $answer3;
    }

    public function getAnswer3()
    {
        return $this->answer3;
    }

    public function setAnswer4($answer4)
    {
        $this->answer4 = $answer4;
    }

    public function getAnswer4()
    {
        return $this->answer4;
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
