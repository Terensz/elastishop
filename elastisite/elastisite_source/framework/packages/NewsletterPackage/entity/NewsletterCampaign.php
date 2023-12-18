<?php
namespace framework\packages\NewsletterPackage\entity;

use framework\component\parent\DbEntity;

class NewsletterCampaign extends DbEntity
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `newsletter_campaign` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `newsletter_id` INT(11) DEFAULT NULL,
        `title` VARCHAR(255) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `created_by` VARCHAR(255) DEFAULT NULL,
        `status` int(2) DEFAULT 0,
        -- `completed_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=63000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    // const ENTITY_ATTRIBUTES = [
    //     'technicalProperties' => array('subscribed')
    // ];

    /**
     * @var int
    */
    protected $id;

    /**
     * @var Newsletter
    */
    protected $newsletter;

    /**
     * @var string
    */
    protected $title;

    /**
     * @var \DateTime
     * This object is first saved at this time.
    */
    protected $createdAt;

    /**
     * @var string
    */
    protected $createdBy;

    /**
     * @var int
     * Can be disabled.
    */
    protected $status;

    public function __construct()
    {
        $this->createdAt = $this->getCurrentTimestamp();
        $this->status = self::STATUS_ACTIVE;
        // dump($this->getContainer());exit;
        if ($this->getContainer()->getUser()) {
            $this->createdBy = (string)$this->getContainer()->getUser()->getUsername();
            // dump($this);exit;
        }
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setNewsletter(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    public function getNewsletter()
    {
        return $this->newsletter;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
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
