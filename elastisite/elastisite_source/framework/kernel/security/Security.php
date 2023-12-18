<?php

namespace framework\kernel\security;

use App;
use framework\component\exception\ElastiException;
use framework\kernel\component\Kernel;
use framework\kernel\security\repository\SecurityEventRepository;
use framework\packages\ToolPackage\service\Mailer;

use framework\kernel\security\controller\SecurityController;
use framework\kernel\utility\BasicUtils;
use framework\packages\WebshopPackage\repository\ProductRepository;
use framework\packages\WebshopPackage\repository\ProductImageRepository;

class Security extends Kernel
{
    const PENALTY_POINT_LIMIT = 10;
    private $previousPenaltyPoints = 0;

    public function getPreviousPenaltyPoints()
    {
        return $this->previousPenaltyPoints;
    }

    public function __construct()
    {
        $this->getContainer()->setService('framework/kernel/security/repository/SecurityEventRepository');
        $this->getContainer()->wireService('framework/packages/ToolPackage/service/Mailer');
        // $this->preparationSecurity();
    }

    public function checkAllowedDomain()
    {
        $fullDomain = $this->getContainer()->getUrl()->getFullDomain();
        // dump($this->getSettings()->get('Security_baseDomain'));exit;

        if (!$this->getContainer()->isAjax()) {
            if (!$this->getSettings()->get('Security_baseDomain')) {
                $this->getSettings()->set('Security_baseDomain', $fullDomain);
            }
        }

        $siteBaseDomain = $this->getSettings()->get('Security_baseDomain');
        $siteMainDomain = BasicUtils::explodeAndGetElement($siteBaseDomain, '.', 'last');

        $configuredAllowedDomains = App::getContainer()->getConfig()->getProjectData('allowedDomains');
        $allowedDomains = [];

        if (is_array($configuredAllowedDomains)) {
            $allowedDomains = array_merge($allowedDomains, $configuredAllowedDomains);
        }

        $allowedDomains = array_merge($allowedDomains, [$siteMainDomain]);

        // dump($allowedDomains);
        // dump($this->getContainer()->getUrl()->getFullDomain());
        $result = in_array($fullDomain, $allowedDomains) ? true : false;
        if (!$result && $this->getContainer()->getConfig()->getProjectData('allowAllSubdomains')) {
            $mainDomain = BasicUtils::explodeAndGetElement($fullDomain, '.', 'last');
            $result = in_array($mainDomain, $allowedDomains) ? true : false;
        }

        // dump($mainDomain);
        // dump($allowedDomains);
        // dump($this->getContainer()->getConfig()->getProjectData('allowAllSubdomains'));
        // dump($result);exit;

        return $result;
    }

    public function preparationSecurity()
    {
        try {
            $securityEventRepo = $this->getContainer()->getService('SecurityEventRepository');
            $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
    
            if (!$this->checkAllowedDomain()) {
                if ($this->getContainer()->isAjax()) {
                    $securityEventHandler->addEvent('URL_IS_COUNTERFEIT', 'url', $this->getContainer()->getUrl()->getHttpDomain());
                }
            }
            
            $usersSecurityEvents = $securityEventRepo->findBy(['conditions' => [
                ['key' => 'visitor_code', 'value' => $this->getSession()->get('visitorCode')]
            ]]);
            foreach ($usersSecurityEvents as $usersSecurityEvent) {
                if ($usersSecurityEvent->getExpiresAt() >= date('Y-m-d H:I:s')) {
                    $this->previousPenaltyPoints += $usersSecurityEvent->getPenaltyPoints();
                }
            }
    
            $this->banOrWarn('preparationSecurity');
        }
        catch (ElastiException $e) {
            if ($e->getCode() == 1660) {
                return true;
                // dump($e);exit;
            }
            // dump($e->getCode());exit;
        }
    }

    public function finishingSecurity()
    {
        $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
        $securityEventHandler->storeEvents();
        $this->banOrWarn('finishingSecurity');
    }

    public function banOrWarn($source = null)
    {
        $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
        $this->setService('framework/kernel/security/controller/SecurityController');
        $securityController = $this->getService('SecurityController');

        // dump($this->getPreviousPenaltyPoints()); exit;

        if ($securityEventHandler->getFreshPenaltyPoints() == 0 && $this->getPreviousPenaltyPoints() < $this::PENALTY_POINT_LIMIT) {
            return true;
        } else {
            if ($this->getPreviousPenaltyPoints() == 0) {
                if ($source != 'finishingSecurity') {
                    $this->finishingSecurity();
                }
                return $securityController->attackerWarningAction();
            } elseif (($this->getPreviousPenaltyPoints() + $securityEventHandler->getFreshPenaltyPoints()) >= $this::PENALTY_POINT_LIMIT) {
                if ($source != 'finishingSecurity') {
                    $this->finishingSecurity();
                }
                return $securityController->attackerBanAction();
            } else {
            }
        }
        return ($this->getPreviousPenaltyPoints() == 0 && $securityEventHandler->getFreshPenaltyPoints() > 0) ? true : false;
    }

    public function devMailTestAction()
    {
        $this->wireService('ToolPackage/service/Mailer');
        $mailer = new Mailer();
        $mailer->setSubject('Alma');
        $mailer->setBody('Alma');
        $mailer->addRecipient('terencecleric@gmail.com', 'Terensz TESZT');
        // $mailer->addRecipient('papp.ferenc39@upcmail.hu', 'Terensz TESZT');
        $mailer->send();
        dump($mailer);exit;
    }

    function test() {
       // Tesztek csak ugy:
        // -----------------------
        // $this->getContainer()->wireService('framework/packages/UserPackage/entity/User');
        // $this->getContainer()->wireService('framework/packages/UserPackage/entity/UserAccount');
        // $this->getContainer()->wireService('framework/packages/UserPackage/repository/UserAccountRepository');
        // $this->getContainer()->wireService('framework/packages/UserPackage/entity/Person');
        // $this->getContainer()->wireService('framework/packages/UserPackage/entity/Address');

        // dump($this->getContainer()->getSession()->get('visitorCode'));exit;
        // $em = $this->getEntityManager();

        // $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
        // dump($securityEventHandler->getEvents());exit;

        // $this->getContainer()->wireService('framework/packages/UserPackage/repository/PersonRepository');
        // $pr = new PersonRepository();
        // $p = $pr->findAll();
        // dump($p);

        // $userAccountRepo = new UserAccountRepository();

        // $addr = new Address();
        // $addr->setPostalAddress('Budapest, Kisjoska utca 23');
        // $person = new Person();
        // $person->addAddress($addr);
        // $person->setFullName('Kisjanos Aladar');
        // $person->setUsername('kisali');
        // $person->setEmail('csorbadzsi12@asdasd.hu');
        // $acc = $userAccountRepo->createNewEntity();
        // $acc->setPerson($person);
        // $acc->setCode('WASDASD24234');
        // $acc->setRegisteredAt($this->getCurrentTimestamp());
        // $acc->setStatus(1);
        // $userAccountRepo->store($acc);
        // dump($acc);exit;

        // $securityEventRepo = new SecurityEventRepository();
        // $a = $securityEventRepo->findAll();
        // dump($a);exit;

        // $b = $userAccountRepo->findAll();
        // dump($b);exit;

        // $this->getContainer()->wireService('framework/packages/UserPackage/repository/TestUserAccountRepository');
        // $testUserAccRepo = new TestUserAccountRepository();
        // $b = $testUserAccRepo->findAll();
        // dump($b);exit;

        // dump($this->getContainer()->getUser());exit;
        // dump($em->findBy());exit;
        // dump($this->getSession()->get('visitorCode'));
        // dump('Security');exit;
    }
}
