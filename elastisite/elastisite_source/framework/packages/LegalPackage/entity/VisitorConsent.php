<?php
namespace framework\packages\LegalPackage\entity;

// use framework\component\parent\Service;

use App;
use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class VisitorConsent extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `visitor_consent` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `visitor_code` varchar(100) DEFAULT NULL,
        `user_account_id` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=90000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $website;
    protected $visitorCode;
    protected $userAccount;
    protected $visitorConsentAcceptance = [];
    // protected $submittedAt;

    public function __construct()
    {
        $this->website = App::getWebsite();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setVisitorCode($visitorCode)
    {
        $this->visitorCode = $visitorCode;
    }

    public function getVisitorCode()
    {
        return $this->visitorCode;
    }

    public function setUserAccount(UserAccount $userAccount = null)
    {
        $this->userAccount = $userAccount;
    }

    public function getUserAccount()
    {
        return $this->userAccount;
    }


    public function addVisitorConsentAcceptance(VisitorConsentAcceptance $visitorConsentAcceptance)
    {
        $this->visitorConsentAcceptance[] = $visitorConsentAcceptance;
    }

    public function getVisitorConsentAcceptance() : array
    {
        return $this->visitorConsentAcceptance;
    }

    // public function setSubmittedAt($submittedAt)
    // {
    //     $this->submittedAt = $submittedAt;
    // }

    // public function getSubmittedAt()
    // {
    //     return $this->submittedAt;
    // }
}
