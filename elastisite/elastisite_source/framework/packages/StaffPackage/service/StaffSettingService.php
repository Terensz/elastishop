<?php
namespace framework\packages\StaffPackage\service;

use App;
use framework\component\helper\DateUtils;
use framework\component\parent\Service;

class StaffSettingService extends Service
{
    const WEEK_SERIAL_BASED_ON_YEAR = [
        'key' => 'Year',
        'translationReference' => 'year'
    ];
    const WEEK_SERIAL_BASED_ON_STAFF_MEMBER_TRAINED_AT = [
        'key' => 'StaffMemberTrainedAt',
        'translationReference' => 'staff.member.trained.at'
    ];
    const WEEK_SERIAL_BASED_ON_CUSTOM_FIRST_STAT_WEEK_START_TIME = [
        'key' => 'CustomFirstStatWeekStartTime',
        'translationReference' => 'custom.first.stat.week.start.time'
    ];

    const WEEK_START_DAY = DateUtils::DAY_THURSDAY;
    const WEEK_START_TIME = '14:00:00';
    const WEEK_SERIAL_BASED_ON = self::WEEK_SERIAL_BASED_ON_YEAR['key'];
    const CUSTOM_FIRST_STAT_WEEK_START_DATE = '2020-01-01';
    const ALLOW_PAGE_CODE_USAGE_FOR_ONE_WEEK_ONLY = false;
    const ALLOW_EDITING_EXPIRED_WEEK = true;

    public static $baseSettings = array(
        'StaffPackage_WeekStartDay' => self::WEEK_START_DAY,
        'StaffPackage_WeekStartTime' => self::WEEK_START_TIME,
        'StaffPackage_WeekSerialBasedOn' => self::WEEK_SERIAL_BASED_ON,
        'StaffPackage_CustomFirstStatWeekStartTime' => self::CUSTOM_FIRST_STAT_WEEK_START_DATE,
        'StaffPackage_AllowPageCodeUsageForOneWeekOnly' => self::ALLOW_PAGE_CODE_USAGE_FOR_ONE_WEEK_ONLY,
        'StaffPackage_AllowEditingExpiredWeek' => self::ALLOW_EDITING_EXPIRED_WEEK
    );

    public static function getSetting($key, $convertNonTextValues = true)
    {
        $container = App::getContainer();
        $container->setService('FrameworkPackage/service/SettingsService');
        $settingsService = $container->getService('SettingsService');
        $value = $settingsService->get($key);

        if (!$value) {
            $value = isset(self::$baseSettings[$key]) ? self::$baseSettings[$key] : null;
        }
        
        if ($convertNonTextValues) {
            $value = $settingsService->convertValueFromText($value);
        }

        return $value;
    }

    public static function getStaffSettingsArray()
    {
        App::getContainer()->setService('StaffPackage/service/StaffSettingService');
        $staffSettingService = App::getContainer()->getService('StaffSettingService');
        $settingsArray = [];
        foreach ($staffSettingService::$baseSettings as $settingKey => $settingValue) {
            $settingsArray[$settingKey] = $staffSettingService->getDisplayedSetting($settingKey);
        }

        return $settingsArray;
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
