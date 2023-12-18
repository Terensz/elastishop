<?php
namespace framework\packages\VisitorPackage\service;

use App;
use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\packages\VisitorPackage\entity\Referer;
use framework\packages\VisitorPackage\entity\Visit;

class VisitorLogService extends Kernel
{
    public function init()
    {
        $visitorCode = $this->getContainer()->getSession()->get('visitorCode');
        $routeParamChain = $this->getContainer()->getFailedRoute() ? $this->getContainer()->getFailedRoute().' ('.$this->getContainer()->getUrl()->getPageRoute()->getParamChain().')' : $this->getContainer()->getUrl()->getPageRoute()->getParamChain();
        // $actualRouteName = $this->getContainer()->getRouting()->getActualRoute()->getName();
        // $pageRoute = $this->getContainer()->getUrl()->getPageRoute();
        // $actualRoute = $this->getContainer()->getRouting()->getActualRoute();
        $this->wireService('VisitorPackage/entity/Visit');
        $newVisit = new Visit();
        $visitRepo = $newVisit->getRepository();
		$dateTime = $this->getCurrentTimestamp();
		$date = $dateTime->format('Y-m-d');

        // dump($routeParamChain); //exit;

        $foundVisit = $visitRepo->findOneBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'visitor_code', 'value' => $visitorCode],
            ['key' => 'route_param_chain', 'value' => $routeParamChain],
            ['key' => 'visited_at', 'value' => $date],
            // ['key' => 'visitor_code', 'value' => $visitorCode],
        ]]);

        // dump($foundVisit); exit;

        if ($foundVisit) {
            $foundVisit->setNumberOfVisits($foundVisit->getNumberOfVisits() + 1);
        } else {
            $newVisit->setNumberOfVisits(1);
        }

        $visit = $foundVisit ? : $newVisit;

        // dump($visit); //exit;

        $visit->setRouteParamChain($routeParamChain);
        // $visit->setRouteParamChain($this->getContainer()->getRouting()->getActualRoute()->getName());
        $visit->setRouteName($this->getContainer()->getUrl()->getPageRoute()->getName());
        $visit->setVisitorCode($visitorCode);
        // if ($this->getContainer()->getUser()->getUserAccount()->getId()) {
        //     $visit->setUserAccount($this->getContainer()->getUser()->getUserAccount());
        // }
        
        // dump($repo);exit;
        $visitRepo->store($visit);
        // $refererUrlService->saveReferer();

        // if (isset($_SERVER['HTTP_REFERER'])) {
        //     // dump($this->getContainer()->getUrl());exit;
        //     $this->wireService('VisitorPackage/entity/Referer');
        //     $referer = new Referer();
        //     $referer->setRouteParamChain($routeParamChain);
        //     $referer->setRefererUrl($_SERVER['HTTP_REFERER']);
        //     $referer->setVisitorCode($visitorCode);
        //     if ($this->getContainer()->getUser()->getUserAccount()->getId()) {
        //         $referer->setUserAccount($this->getContainer()->getUser()->getUserAccount());
        //     }
        //     $refererRepo = $referer->getRepository();
        //     // dump($repo);exit;
        //     $refererRepo->store($referer);
        // }
    }
}
