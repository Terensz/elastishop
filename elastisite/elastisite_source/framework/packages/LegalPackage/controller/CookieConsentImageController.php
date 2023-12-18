<?php
namespace framework\packages\LegalPackage\controller;

use App;
use framework\component\parent\AccessoryController;
use framework\kernel\utility\FileHandler;
use framework\packages\SiteBuilderPackage\repository\ContentEditorRepository;
use framework\packages\ToolPackage\service\ImageService;
use framework\packages\SiteBuilderPackage\service\ContentEditorImageService;

class CookieConsentImageController extends AccessoryController
{
    /**
    * Route: [name: cookieConsent_showLogo, paramChain: /cookieConsent/showLogo/{subscriber}]
    */
    public function cookieConsentShowLogoAction($subscriber)
    {
        $pathToFile = FileHandler::completePath('projects/'.App::getWebProject().'/view/icon/consentAcceptanceRequestSubscribers/'.$subscriber.'/icon.png', 'projects');
        $this->getContainer()->wireService('ToolPackage/service/ImageService');
        $imageService = new ImageService();
        if (!is_file($pathToFile)) {
            return null;
        }

        return $imageService->loadPathToImage($pathToFile);
    }
}
