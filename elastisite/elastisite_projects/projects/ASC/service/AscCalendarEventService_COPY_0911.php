<?php
namespace projects\ASC\service;

use App;
use framework\component\helper\DateUtils;
use framework\component\parent\Service;
use framework\packages\UserPackage\entity\UserAccount;
use projects\ASC\repository\AscUnitRepository;

class AscCalendarEventService extends Service
{
    const DASHBOARD_PERIOD_IN_HOURS = 168;

    public static $ascUnitRepository;

    public static function getAscUnitRepository()
    {
        if (self::$ascUnitRepository) {
            return self::$ascUnitRepository;
        }

        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        self::$ascUnitRepository = new AscUnitRepository();

        return self::$ascUnitRepository;
    }

    /**
     * Dashboard data
    */

    public static function getDashboardData(UserAccount $userAccount, $ascScale = null)
    {
        App::getContainer()->wireService('UserPackage/entity/UserAccount');
        App::getContainer()->wireService('projects/ASC/service/AscRequestService');

        // $todayDate = date('Y-m-d');
        // $periodStartDateTime = new \DateTime($todayDate);
        // $periodStartDateTime->modify("-" . self::DASHBOARD_PERIOD_IN_HOURS . " hours");
        // $periodStart = $periodStartDateTime->format('Y-m-d');
        // $periodEndDateTime = new \DateTime($todayDate);
        // $periodEndDateTime->modify("+" . self::DASHBOARD_PERIOD_IN_HOURS . " hours");
        // $periodEnd = $periodEndDateTime->format('Y-m-d');
        // $dayOfWeek = date('l');

        $processedRequestData = AscRequestService::getProcessedRequestData();
    
        $todaysOneTimeCalendarEventData = AscCalendarEventService::getTodaysOneTimeCalendarEventData($userAccount, $ascScale);
        $todaysRecurringCalendarEventData = AscCalendarEventService::getTodaysRecurringCalendarEventData($userAccount, $ascScale);
        $expiredOneTimeCalendarEventData = AscCalendarEventService::getExpiredOneTimeCalendarEventData($userAccount, $ascScale);
        $expiredRecurringCalendarEventData = AscCalendarEventService::getExpiredRecurringCalendarEventData($userAccount, $ascScale);
        $closedCalendarEventData = AscCalendarEventService::getClosedCalendarEventData($userAccount, $ascScale);
        $postponedCalendarEventData = AscCalendarEventService::getPostponedCalendarEventData($userAccount, $ascScale);

        // dump($todaysOneTimeCalendarEventData);exit;

        $return = [
            'ascScale' => $processedRequestData['ascScale'],
            'todaysOneTimeCalendarEventData' => $todaysOneTimeCalendarEventData,
            'todaysRecurringCalendarEventData' => $todaysRecurringCalendarEventData,
            'expiredOneTimeCalendarEventData' => $expiredOneTimeCalendarEventData,
            'expiredRecurringCalendarEventData' => $expiredRecurringCalendarEventData,
            'closedCalendarEventData' => $closedCalendarEventData,
            'postponedCalendarEventData' => $postponedCalendarEventData,
            'sumTodays' => (count($todaysOneTimeCalendarEventData) + count($todaysRecurringCalendarEventData)),
            'sumExpired' => (count($expiredOneTimeCalendarEventData) + count($expiredRecurringCalendarEventData)),
            'sumClosed' => count($closedCalendarEventData),
            'sumPostponed' => count($postponedCalendarEventData)
        ];

        // dump($return);exit;

        return $return;
    }

    public static function getTodaysOneTimeCalendarEventData($userAccount, $ascScale)
    {
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $data = AscUnitRepository::getOneTimeCalendarEventData($userAccount, $ascScale);
        $repo = self::getAscUnitRepository();
        $data = $repo->appendAscUnitObject($data);

        return $data;
    }
    
    public static function getTodaysRecurringCalendarEventData($userAccount, $ascScale)
    {
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $data = AscUnitRepository::getRecurringCalendarEventData($userAccount, $ascScale);
        $repo = self::getAscUnitRepository();
        $data = $repo->appendAscUnitObject($data);

        return $data;
    }

    public static function getExpiredOneTimeCalendarEventData($userAccount, $ascScale)
    {
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $data = AscUnitRepository::getExpiredOneTimeCalendarEventData($userAccount, $ascScale);
        $repo = self::getAscUnitRepository();
        $data = $repo->appendAscUnitObject($data);

        return $data;
    }

    public static function getExpiredRecurringCalendarEventData($userAccount, $ascScale)
    {
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $data = AscUnitRepository::getExpiredRecurringCalendarEventData($userAccount, $ascScale);
        $repo = self::getAscUnitRepository();
        $data = $repo->appendAscUnitObject($data);

        return $data;
    }

    public static function getClosedCalendarEventData($userAccount, $ascScale)
    {
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $data = AscUnitRepository::getOneTimeCalendarEventData($userAccount, $ascScale);
        $repo = self::getAscUnitRepository();
        $data = $repo->appendAscUnitObject($data);

        return $data;
    }

    public static function getPostponedCalendarEventData($userAccount, $ascScale)
    {
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $data = AscUnitRepository::getOneTimeCalendarEventData($userAccount, $ascScale);
        $repo = self::getAscUnitRepository();
        $data = $repo->appendAscUnitObject($data);

        return $data;
    }
}