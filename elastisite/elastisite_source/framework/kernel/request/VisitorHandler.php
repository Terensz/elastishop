<?php
namespace framework\kernel\request;

use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\packages\VisitorPackage\entity\Visitor;
use framework\packages\UserPackage\entity\UserAccount;
// use framework\packages\BasicPackage\repository\CountryRepository;

class VisitorHandler extends Kernel
{
    public function __construct()
    {
        if (!$this->getSession()->get('visitorCode')) {
            $this->wireService('VisitorPackage/entity/Visitor');
            $dbm = $this->getContainer()->getKernelObject('DbManager');
            $countryCode = isset($this->getSession()->get('geoIpInfo')['country_code']) ? $this->getSession()->get('geoIpInfo')['country_code'] : null;
            if ($dbm->getConnection() && $dbm->tableExists('visitor')) {
                $visitor = new Visitor();
                // $visitor->setUserAccount($this->getContainer()->getUser()->getUserAccount());
                $visitorCode = $this->createVisitorCode($visitor);
                $this->getSession()->set('visitorCode', $visitorCode);
                $visitor->setCode($visitorCode);
                $visitor->setIpAddress($_SERVER['REMOTE_ADDR']);
                $visitor->setCountryCode($countryCode);
                $visitor->setFirstVisit($this->getCurrentTimestamp());
                $visitor->getRepository()->store($visitor);
            }
        } 
        // else {
        //     $visitorCode = $this->getSession()->get('visitorCode');
        // }
    }

    public function createVisitorCode($visitor)
    {
        $code = BasicUtils::generateRandomString(8);
        $isCode = $visitor->getRepository()->isCode($code);
        return $isCode ? $this->createVisitorCode($visitor) : $code;
    }
}
