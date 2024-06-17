<?php
namespace framework\packages\FrameworkPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\FrameworkPackage\service\LanguageService;

class LocaleHandlerWidgetController extends WidgetController
{
    public function localeHandlerSwitchAction($locale)
    {
        App::getContainer()->wireService('FrameworkPackage/service/LanguageService');
        if (!isset(LanguageService::DATA[$locale])) {
            return false;
        }
        App::getContainer()->getSession()->set('public_locale', $locale);
        // dump(LanguageService::DATA);
        // $scriptsFile = App::getContainer()->isGranted('viewSiteHelperContent') ? 'siteHelpersScripts' : 'visitorsScripts'; 
        $response = [
            'view' => '',
            'data' => [
            ]
        ];

        return $this->widgetResponse($response);
    }
}
