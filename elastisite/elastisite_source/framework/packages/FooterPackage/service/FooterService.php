<?php

namespace framework\packages\FooterPackage\service;

use App;
use framework\component\parent\Service;

class FooterService extends Service
{
    public static $settings = array(
        'FooterPackage_ownWebsiteLink' => null,
        'FooterPackage_ownWebsiteName' => null,
        'FooterPackage_facebookLink' => null,
        'FooterPackage_twitterLink' => null,
        'FooterPackage_googleLink' => null,
        'FooterPackage_instagramLink' => null,
        'FooterPackage_linkedinLink' => null,
        'FooterPackage_githubLink' => null
    );

    public static function getSetting($key, $convertNonTextValues = true)
    {
        $container = App::getContainer();
        $container->setService('FrameworkPackage/service/SettingsService');
        $settings = $container->getService('SettingsService');
        $value = $settings->get($key);

        if (!$value) {
            $value = isset(self::$settings[$key]) ? self::$settings[$key] : null;
        }
        
        if ($convertNonTextValues) {
            $value = $settings->convertValueFromText($value);
        }

        // dump(self::$settings);
        // dump($value);
        // dump($settings);

        return $value;
    }

    public static function getDisplayedSetting($key)
    {
        $value = self::getSetting($key, false);

        $container = App::getContainer();
        $container->setService('FrameworkPackage/service/SettingsService');
        $settingsService = $container->getService('SettingsService');
        $value = $settingsService->convertValueToText($value);

        return trans($value);
    }
}