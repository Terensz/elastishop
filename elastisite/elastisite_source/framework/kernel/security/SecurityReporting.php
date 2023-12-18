<?php
namespace framework\kernel\security;

use framework\kernel\component\Kernel;

class SecurityReporting extends Kernel
{
    const SECURITY_STATUS_OK = 100;
    const SECURITY_STATUS_ILLEGAL_POST_ATTEMPT = 201;
    const SECURITY_STATUS_SQL_INJECTION_ATTEMPT = 202;

    private $securityStatus;
    private $events;

    public function __construct()
    {
        $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
        $events = $securityEventHandler->getEvents();
        if ($events == array()) {
            $this->securityStatus = true;
        } else {
            # @todo!
            $this->events = $events;
        }
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function getSecurityStatus()
    {
        if ($this->securityStatus) {
            return true;
        } else {
            return false;
        }
    }
}
