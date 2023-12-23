<?php
namespace projects\Meheszellato\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\Person;

class NewsSubscription extends DbEntity
{
    const STATUS_CODE_CONVERSIONS = [
        '0' => 'disabled',
        '1' => 'active',
        '2' => 'proven'
    ];

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `news_subscription` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `visitor_code` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `person_id` int(11) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` smallint(2) DEFAULT 0,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=38000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    private $id;
    private $visitorCode;
    private $person;
    private $createdAt;
    private $status;

    public function __construct()
    {
        $this->createdAt = $this->getCurrentTimestamp();
        $this->visitorCode = $this->getContainer()->getSession()->get('visitorCode');
        $this->status = 1;
        // dump($this);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setVisitorCode($visitorCode)
    {
        $this->visitorCode = $visitorCode;
    }

    public function getVisitorCode()
    {
        return $this->visitorCode;
    }

    public function setPerson(Person $person = null)
    {
        $this->person = $person;
    }

    public function getPerson()
    {
        return $this->person;
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
