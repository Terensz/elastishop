<?php
namespace framework\packages\NewsletterPackage\entity;

use framework\component\parent\DbEntity;

class Newsletter extends DbEntity
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `newsletter` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `subject` VARCHAR(255) DEFAULT NULL,
        `body` TEXT DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` int(2) DEFAULT 0,
        -- `completed_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=61000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    // const ENTITY_ATTRIBUTES = [
    //     'technicalProperties' => array('subscribed')
    // ];

    protected $id;

    /**
     * @var string
    */
    protected $subject;

    /**
     * @var string
    */
    protected $body;

    /**
     * @var \DateTime
     * This object is first saved at this time.
    */
    protected $createdAt;

    /**
     * @var int
     * Can be disabled.
    */
    protected $status;

    public function __construct()
    {
        $this->createdAt = $this->getCurrentTimestamp();
        $this->status = self::STATUS_ACTIVE;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
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
