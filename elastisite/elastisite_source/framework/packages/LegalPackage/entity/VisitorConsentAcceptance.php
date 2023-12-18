<?php
namespace framework\packages\LegalPackage\entity;

// use framework\component\parent\Service;
use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class VisitorConsentAcceptance extends DbEntity
{
    const REQUESTED_FOR_ACCEPTING_3RD_PARTY_COOKIES = 'accept_3rd_party_cookies';

    const ACCEPTANCE_ACCEPTED = 'accepted';

    const ACCEPTANCE_REFUSED = 'refused';

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `visitor_consent_acceptance` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `visitor_consent_id` int(11) DEFAULT NULL,
        `request_category` varchar(100) DEFAULT NULL,
        `request_subscriber` varchar(100) DEFAULT NULL,
        `acceptance` varchar(10) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=91000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $visitorConsent;
    protected $requestCategory;
    protected $requestSubscriber;
    protected $acceptance;
    protected $createdAt;

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

    public function setVisitorConsent(VisitorConsent $visitorConsent)
    {
        $this->visitorConsent = $visitorConsent;
    }

    public function getVisitorConsent()
    {
        return $this->visitorConsent;
    }

    public function setRequestCategory($requestCategory)
    {
        $this->requestCategory = $requestCategory;
    }

    public function getRequestCategory()
    {
        return $this->requestCategory;
    }

    public function setRequestSubscriber($requestSubscriber)
    {
        $this->requestSubscriber = $requestSubscriber;
    }

    public function getRequestSubscriber()
    {
        return $this->requestSubscriber;
    }

    public function setAcceptance($acceptance)
    {
        $this->acceptance = $acceptance;
    }

    public function getAcceptance()
    {
        return $this->acceptance;
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
