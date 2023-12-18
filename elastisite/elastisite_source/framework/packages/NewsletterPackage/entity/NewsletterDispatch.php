<?php
namespace framework\packages\NewsletterPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\NewsletterPackage\entity\NewsletterDispatchProcess;
use framework\packages\UserPackage\entity\UserAccount;

class NewsletterDispatch extends DbEntity
{
    const STATUS_PENDING = 1;
    const STATUS_SENT = 2;
    const STATUS_DISABLED = 0;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `newsletter_dispatch` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `newsletter_dispatch_process_id` int(11) DEFAULT NULL,
        `user_account_id` int(11) DEFAULT NULL,
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
     * @var NewsletterDispatchProcess
    */
    protected $newsletterDispatchProcess;

    /**
     * @var UserAccount
    */
    protected $userAccount;

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
        $this->status = self::STATUS_PENDING;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setNewsletterDispatchProcess(NewsletterDispatchProcess $newsletterDispatchProcess)
    {
        $this->newsletterDispatchProcess = $newsletterDispatchProcess;
    }

    public function getNewsletterDispatchProcess()
    {
        return $this->newsletterDispatchProcess;
    }

    public function setUserAccount(UserAccount $userAccount = null)
    {
        $this->userAccount = $userAccount;
    }

    public function getUserAccount()
    {
        return $this->userAccount;
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
