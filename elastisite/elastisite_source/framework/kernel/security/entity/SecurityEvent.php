<?php
namespace framework\kernel\security\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class SecurityEvent extends DbEntity
{
    // const ENTITY_ATTRIBUTES = [
    //     'repositoryPath' => 'framework/kernel/security/repository/SecurityEventRepository',
    //     'relations' => [
    //         'UserAccount' => [
    //             'targetClass' => UserAccount::class,
    //             'association' => 'manyToOne',
    //             'relationBinderTable' => false,
    //             'targetIdField' => 'id',
    //             'referencedIdField' => 'user_account_id'
    //         ]
    //     ],
    //     'active' => true
    // ];

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `security_event` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `visitor_code` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `country_code` varchar(3) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `city` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `user_account_id` int(11) DEFAULT NULL,
        `event_type` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `penalty_points` int(11) DEFAULT NULL,
        `ip_address` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `x_forwarded_for` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `host` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `page_url` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `widget_url` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `identifier` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `description` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `expires_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1300 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $visitorCode;
    protected $countryCode;
    protected $city;
    protected $userAccount;
    protected $eventType;
    protected $penaltyPoints;
    protected $ipAddress;
    protected $xForwardedFor;
    protected $host;
    protected $pageUrl;
    protected $widgetUrl;
    protected $identifier;
    protected $description;
    protected $createdAt;
    protected $expiresAt;

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

    public function setVisitorCode($visitorCode)
    {
        $this->visitorCode = $visitorCode;
    }

    public function getVisitorCode()
    {
        return $this->visitorCode;
    }

    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setUserAccount(UserAccount $userAccount)
    {
        $this->userAccount = $userAccount;
    }

    public function getUserAccount()
    {
        return $this->userAccount;
    }

    public function setEventType($eventType)
    {
        $this->eventType = $eventType;
    }

    public function getEventType()
    {
        return $this->eventType;
    }

    public function setPenaltyPoints($penaltyPoints)
    {
        $this->penaltyPoints = $penaltyPoints;
    }

    public function getPenaltyPoints()
    {
        return $this->penaltyPoints;
    }

    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    public function setXForwardedFor($xForwardedFor)
    {
        $this->xForwardedFor = $xForwardedFor;
    }

    public function getXForwardedFor()
    {
        return $this->xForwardedFor;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setPageUrl($pageUrl)
    {
        $this->pageUrl = $pageUrl;
    }

    public function getPageUrl()
    {
        return $this->pageUrl;
    }

    public function setWidgetUrl($widgetUrl)
    {
        $this->widgetUrl = $widgetUrl;
    }

    public function getWidgetUrl()
    {
        return $this->widgetUrl;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;
    }

    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

}
