<?php
namespace framework\packages\PaymentPackage\gatewayProviders\Barion\autoScript;

use App;
use framework\component\parent\Service;
use framework\packages\LegalPackage\controller\CookieConsentService;
use framework\packages\LegalPackage\entity\VisitorConsentAcceptance;
use framework\packages\WebshopPackage\service\WebshopService;

class HeadJSController extends Service
{
    const NUMBER = null;

    const AUTO_SCRIPT_CONFIG = [
        'active' => true,
        'location' => 'head'
    ];

    public function createScript()
    {
        $container = App::getContainer();
        $container->wireService('WebshopPackage/service/WebshopService');
        $mode = WebshopService::isWebshopTestMode('HeadJSController->createScript') ? 'test' : 'prod';
        $pathToConfigFile = $this->getContainer()->getPathBase('config').'/sysadmin/payments/'.$mode.'/Barion.txt';
        $configReader = App::$configReader;
        $config = $configReader->read($pathToConfigFile);
        $pageRoute = App::getContainer()->getRouting()->getPageRoute()->getName();

        $this->wireService('LegalPackage/service/CookieConsentService');
        $this->wireService('LegalPackage/entity/VisitorConsentAcceptance');
        if (in_array($pageRoute, CookieConsentService::PAGES_NOT_NEQUIRING_COOKIE_INFO)) {
            return '';
        }

        $thirdPartyCookiesAcceptance = CookieConsentService::findThirdPartyCookiesAcceptances(false, 'Barion');
        // dump($thirdPartyCookiesAcceptance);
        if (!$thirdPartyCookiesAcceptance || ($thirdPartyCookiesAcceptance && $thirdPartyCookiesAcceptance->getAcceptance() == VisitorConsentAcceptance::ACCEPTANCE_REFUSED)) {
            return '';
        }

        $viewPath = 'framework/packages/PaymentPackage/gatewayProviders/Barion/autoScript/view/headJS.php';
        $view = $this->renderView($viewPath, [
            'barionPixelId' => $config['pixelId']
        ]);

        return $view;
    }
}