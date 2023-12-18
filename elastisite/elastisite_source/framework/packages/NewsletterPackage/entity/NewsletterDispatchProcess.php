<?php
namespace framework\packages\NewsletterPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\NewsletterPackage\entity\NewsletterCampaign;

class NewsletterDispatchProcess extends DbEntity
{
    const STATUS_ACTIVE = 1;
    const STATUS_PAUSED = 2;
    const STATUS_CREATED = 3;
    const STATUS_DISABLED = 0;

    const MODE_TEST = 1;
    const MODE_PRODUCTION = 2;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `newsletter_dispatch_process` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `newsletter_campaign_id` int(11) DEFAULT NULL,
        `mode` int(2) DEFAULT NULL,
        `total_dispatches_count` int(11) DEFAULT NULL,
        `dispatches_sent` int(11) DEFAULT NULL,
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
     * @var NewsletterCampaign
    */
    protected $newsletterCampaign;

    /**
     * @var string
    */
    protected $mode;

    /**
     * @var int
    */
    protected $totalDispatchesCount;

    /**
     * @var int
    */
    protected $dispatchesSent;

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
        $this->mode = self::MODE_TEST;
        $this->createdAt = $this->getCurrentTimestamp();
        $this->status = self::STATUS_CREATED;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setNewsletterCampaign(NewsletterCampaign $newsletterCampaign)
    {
        $this->newsletterCampaign = $newsletterCampaign;
    }

    public function getNewsletterCampaign()
    {
        return $this->newsletterCampaign;
    }

    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function setTotalDispatchesCount($totalDispatchesCount)
    {
        $this->totalDispatchesCount = $totalDispatchesCount;
    }

    public function getTotalDispatchesCount()
    {
        return $this->totalDispatchesCount;
    }

    public function setDispatchesSent($dispatchesSent)
    {
        $this->dispatchesSent = $dispatchesSent;
    }

    public function getDispatchesSent()
    {
        return $this->dispatchesSent;
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
