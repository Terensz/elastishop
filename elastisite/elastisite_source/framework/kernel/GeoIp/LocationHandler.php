<?php
namespace framework\kernel\GeoIp;

use framework\component\parent\Service;
use framework\kernel\GeoIp\GeoIpTool;

class LocationHandler extends Service
{
    private $geoIpInfo;

    public function __construct()
    {
        // $this->handleSessions();
        // dump('Alma');exit;
    }

    public function handleSessions($forceRefresh = false)
    {
        if ($this->getSession()->get('geoIpInfo') && (!isset($this->getSession()->get('geoIpInfo')['ip_address']) || $_SERVER['REMOTE_ADDR'] != $this->getSession()->get('geoIpInfo')['ip_address'])) {
            $forceRefresh = true;
        }
        if ($forceRefresh || (!$this->getSession()->get('geoIpInfo') || !$this->getSession()->get('countryClassification'))) {
            $this->geoIpInfo = GeoIpTool::getIpInfo();
            $this->setGeoIpInfo();
            $this->initCountryClassification();
        }
        // dump($this->getSession()->get('geoIpInfo'));exit;
    }

    public function getGeoIpTool()
    {
        return $this->geoIpInfo;
    }

    public function setGeoIpInfo()
    {
        $this->getSession()->set('geoIpInfo', $this->geoIpInfo);
    }

    public function initCountryClassification()
    {
        $this->getSession()->set('countryClassification', $this->getCountryClassification());
    }

    public function getCountryClassification()
    {
        $ipInfo = $this->geoIpInfo;
        if ($ipInfo && in_array($ipInfo['country_code'], GeoIpTool::$notTrustedCountryCodes)) {
            return 'notTrusted';
        }
        return 'trusted';
    }
}
