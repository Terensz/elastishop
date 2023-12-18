<?php
namespace framework\packages\UserPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\TemporaryPerson;
// use framework\packages\UserPackage\entity\UserAccount;

class TemporaryAccount extends DbEntity
{
    // const STATUS_ACTIVE = 1;
    // const STATUS_INACTIVE = 0;
    const STATUS_OPEN = 3;
    const STATUS_CLOSED = 4;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `temporary_account` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `visitor_code` varchar(100) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` smallint(2) DEFAULT ".self::STATUS_OPEN.",
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1300 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    /**
    * @primaryKey
    */
    protected $id;

    /**
    * @var TemporaryPerson
    */
    private $temporaryPerson;

    /**
    * @var string
    */
    private $visitorCode;

    /**
    * @var \DateTime
    */
    private $createdAt;

    /**
    * @var int
    */
    private $status;

    public function __construct()
    {
        $this->status = self::STATUS_OPEN;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTemporaryPerson(TemporaryPerson $temporaryPerson = null)
    {
        $this->temporaryPerson = $temporaryPerson;
    }

    public function getTemporaryPerson()
    {
        return $this->temporaryPerson;
    }

    public function setVisitorCode($visitorCode)
    {
        $this->visitorCode = $visitorCode;
    }

    public function getVisitorCode()
    {
        return $this->visitorCode;
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
