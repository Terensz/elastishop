<?php
namespace framework\kernel\security;

use framework\kernel\component\Kernel;
// use framework\kernel\security\Security;
use framework\kernel\security\entity\SecurityEvent;
use framework\kernel\security\repository\SecurityEventRepository;

class SecurityEventHandler extends Kernel
{
    private $securityEvents = array();
    private $freshPenaltyPoints = 0;

    public $events = array(
        'SQL_INJECTION_ATTEMPT' => array(
            'penaltyPoints' => 5,
            'expirationHours' => 72
        ),
        'NON_LEGIT_POST_KEY' => array(
            'penaltyPoints' => 5,
            'expirationHours' => 48
        ),
        'POST_OUT_OF_RESTRICTIONS' => array(
            'penaltyPoints' => 2,
            'expirationHours' => 1
        ),
        'URL_IS_COUNTERFEIT' => array(
            'penaltyPoints' => 10,
            'expirationHours' => 72
        ),
        'TESTING_FOREIGN_DATA' => array(
            'penaltyPoints' => 2,
            'expirationHours' => 1
        )
    );

    public function __construct()
    {
        $this->getContainer()->wireService('framework/kernel/security/entity/SecurityEvent');
        $this->getContainer()->setService('framework/kernel/security/repository/SecurityEventRepository');
    }

    public function getFreshPenaltyPoints()
    {
        return $this->freshPenaltyPoints;
    }

    public function addEvent($eventType, $identifier, $notice)
    {
        $securityEvent = new SecurityEvent();
        $securityEvent->setEventType($eventType);
        $penaltyPoints = $this->moderatePenalty($this->events[$eventType]['penaltyPoints']);
        $securityEvent->setPenaltyPoints($penaltyPoints);
        $geoIpInfo = $this->getSession()->get('geoIpInfo');
        if ($geoIpInfo) {
            $securityEvent->setCountryCode($geoIpInfo['country_code']);
            $securityEvent->setCity($geoIpInfo['city']);
        }
        $securityEvent->setIpAddress($_SERVER['REMOTE_ADDR']);
        $securityEvent->setXForwardedFor(isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null);
        $securityEvent->setHost(gethostbyaddr($_SERVER['REMOTE_ADDR']));
        $securityEvent->setIdentifier($identifier);
        $securityEvent->setDescription($notice);
        $securityEvent->setVisitorCode($this->getContainer()->getSession()->get('visitorCode'));
        $expiresAt = $this->getCurrentTimestamp();
        $expiresAt->add(new \DateInterval('PT'.((string) $this->events[$eventType]['expirationHours']).'H'));
        $securityEvent->setExpiresAt($expiresAt);
        $this->securityEvents[] = $securityEvent;
        $this->freshPenaltyPoints += $penaltyPoints;
    }

    public function storeEvents()
    {
        $repo = $this->getContainer()->getService('SecurityEventRepository');
        foreach ($this->securityEvents as $securityEvent) {
            if ($this->getRouting()->getPageRoute()) {
                $securityEvent->setPageUrl($this->getRouting()->getPageRoute()->getParamChain());
            }
            if ($this->getRouting()->getActualRoute()) {
                $securityEvent->setWidgetUrl($this->getRouting()->getActualRoute()->getParamChain());
            }
            $repo->store($securityEvent);
        }
    }

    public function moderatePenalty($thisEventsPoints)
    {
        $security = $this->getContainer()->getKernelObject('Security');
        if ($security->getPreviousPenaltyPoints() > 0 || $thisEventsPoints >= $security::PENALTY_POINT_LIMIT) {
            return $thisEventsPoints;
        } else {
            return $this->freshPenaltyPoints > 0 ? 0 : $thisEventsPoints;
        }
    }

    public function secureSchemaProcessing()
    {
        $security = $this->getContainer()->getKernelObject('Security');
        $security->banOrWarn();
    }

    public function getEvents($type = null)
    {
        if (!$type) {
            return !$this->securityEvents ? array() : $this->securityEvents;
        }
        $filteredSecurityEvents = array();
        foreach ($this->securityEvents as $securityEvent) {
            if ($securityEvent->getType() == $type) {
                $filteredSecurityEvents[] = $securityEvent;
            }
        }
        return $filteredSecurityEvents;
    }
}
