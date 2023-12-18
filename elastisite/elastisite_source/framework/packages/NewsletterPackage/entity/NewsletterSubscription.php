<?php
namespace framework\packages\NewsletterPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class NewsletterSubscription extends DbEntity
{
    const NEWSLETTER_TYPE_GENERAL_ADVERTISEMENT = 1;
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `newsletter_subscription` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_account_id` int(11) DEFAULT NULL,
        `subscription_type` int(3) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` int(2) DEFAULT 0,
        -- `completed_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=62000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        'technicalProperties' => array('subscribed')
    ];

    protected $id;

    /**
     * @var bool
     * If this value is NOT true, this object will not be saved by the repo.
    */
    protected $subscribed;

    /**
     * @var UserAccount 
    */
    protected $userAccount;

    /**
     * @var int
     * Later the users might be subscribed on some other stuff.
    */
    protected $subscriptionType;

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
        $this->subscriptionType = self::NEWSLETTER_TYPE_GENERAL_ADVERTISEMENT;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSubscribed($subscribed)
    {
        if ($subscribed == '*no*') {
            $subscribed = false;
        }
        if ($subscribed == '*yes*') {
            $subscribed = true;
        }
        $this->subscribed = $subscribed;
    }

    public function getSubscribed()
    {
        return $this->subscribed;
    }

    public function setUserAccount(UserAccount $userAccount)
    {
        $this->userAccount = $userAccount;
    }

    public function getUserAccount()
    {
        return $this->userAccount;
    }

    public function setSubscriptionType($subscriptionType)
    {
        $this->subscriptionType = $subscriptionType;
    }

    public function getSubscriptionType()
    {
        return $this->subscriptionType;
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
