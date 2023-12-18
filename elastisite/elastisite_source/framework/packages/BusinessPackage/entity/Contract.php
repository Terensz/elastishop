<?php
namespace framework\packages\BusinessPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\Address;
use framework\packages\UserPackage\entity\UserAccount;

class Contract extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `contract` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `contract_number` varchar(40) DEFAULT NULL,
        `started_at` datetime DEFAULT NULL,
        `closed_at` datetime DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` smallint(2) DEFAULT 0,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=38000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $contractNumber;
    protected $startedAt;
    protected $closedAt;
    protected $createdAt;
    protected $status;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setContractNumber($contractNumber)
    {
        $this->contractNumber = $contractNumber;
    }

    public function getContractNumber()
    {
        return $this->contractNumber;
    }

    public function setStartedAt(\DateTime $startedAt = null)
    {
        $this->startedAt = $startedAt;
    }

    public function getStartedAt()
    {
        return $this->startedAt;
    }

    public function setClosedAt(\DateTime $closedAt = null)
    {
        $this->closedAt = $closedAt;
    }

    public function getClosedAt()
    {
        return $this->closedAt;
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
