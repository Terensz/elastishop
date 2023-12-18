<?php
namespace framework\packages\MarketingPackage\service;

use framework\component\parent\Service;
use framework\kernel\utility\FileHandler;

class ConsentAcceptanceRequestSubscriberService extends Service
{
    public function getSubscribers()
    {
        $subscribers = FileHandler::getAllDirNames('framework/packages/MarketingPackage/consentAcceptanceRequestSubscribers', 'source');
        // $path = FileHandler::completePath('framework/packages/LegalPackage/consentAcceptanceRequestSubscribers', 'source');
        // dump($subscribers);exit;

        return $subscribers;
    }
}