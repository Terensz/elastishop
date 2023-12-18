<?php
namespace framework\packages\StaffPackage\service;

use App;
use framework\component\helper\DateUtils;
use framework\component\parent\Service;
use framework\packages\StaffPackage\entity\StaffMember;
use framework\packages\StaffPackage\entity\StaffMemberStat;
use framework\packages\StaffPackage\repository\StaffMemberRepository;
use framework\packages\StaffPackage\repository\StaffMemberStatRepository;
use framework\packages\StatisticsPackage\service\CustomWeekManager;

class StaffMemberStatService extends Service
{
    const CATEGORIES_VALUE_COUNT = 29;

    const PERIOD_UNIT_TYPE_WEEKLY = 'Weekly';
    const PERIOD_UNIT_TYPE_MONTHLY = 'Monthly';

    const DEFAULT_WEEK_INTERVAL = 4;
    const DEFAULT_MONTH_INTERVAL = 4;

    const TREND_INCREASING = 'increasing';
    const TREND_DECREASING = 'decreasing';
    const TREND_NEUTRAL = 'neutral';

    const DEFAULT_MAX_POINTS = 20;

    public $staffMember;
    public $maxPoints = self::DEFAULT_MAX_POINTS;

    public static $staffMemberRepository;
    public static $staffMemberStatRepository;

    public function __construct(StaffMember $staffMember)
    {
        $this->staffMember = $staffMember;
    }

    public function getStaffMemberRepository()
    {
        if (!self::$staffMemberRepository) {
            App::getContainer()->wireService('StaffPackage/repository/StaffMemberRepository');
            self::$staffMemberRepository = new StaffMemberRepository();
        }

        return self::$staffMemberRepository;
    }

    public function getStaffMemberStatRepository()
    {
        if (!self::$staffMemberStatRepository) {
            App::getContainer()->wireService('StaffPackage/repository/StaffMemberStatRepository');
            self::$staffMemberStatRepository = new StaffMemberStatRepository();
        }

        return self::$staffMemberStatRepository;
    }

    /**
     * @var weekId: YYYY-weekNumber. E.g.: 2023-12
    */
    public function getStaffMemberStats($filter = [], $preventDefaultFilters = false)
    {
        if (!isset($filter['periodUnitType'])) {
            $filter['periodUnitType'] = self::PERIOD_UNIT_TYPE_WEEKLY;
        }

        App::getContainer()->wireService('StatisticsPackage/service/CustomWeekManager');

        $currentWeekNumber = (int)CustomWeekManager::getWeekNumber((new \DateTime())->format('Y-m-d'));
        $currentYear = DateUtils::getCurrentYear();
        $currentWeekId = $currentYear . '-' . $currentWeekNumber;
        $staffMemberWeeks = self::getStaffMemberWeeks();

        // if ($filter['periodUnitType'] == self::PERIOD_UNIT_TYPE_WEEKLY) {
        // }

        $filter['toWeekId'] = isset($filter['toWeekId']) ? $filter['toWeekId'] : $currentWeekId;
        /**
         * Azért a biztonság kedvéért megnézzük, hogy létezik-e a megadott toWeekId. Ha nem, akkor az aktuálisat
        */
        if (!self::findInStaffMemberWeeks($staffMemberWeeks, 'weekId', $filter['toWeekId'])) {
            $filter['toWeekId'] = $currentWeekId;
        }

        if (!isset($filter['fromWeekId'])) {
            $filter['fromWeekId'] = CustomWeekManager::getWeekId(-(self::DEFAULT_WEEK_INTERVAL));
        }

        // dump(CustomWeekManager::getWeekId(-28));
        // dump(CustomWeekManager::getWeekId(-27));
        // dump(CustomWeekManager::getWeekId(-26));
        // dump('-------');
        // dump($currentWeekId);
        // dump(CustomWeekManager::getWeekId(-1));
        // dump(CustomWeekManager::getWeekId(-(self::DEFAULT_WEEK_INTERVAL)));
        // dump($staffMemberWeeks);exit;

        // dump($staffMemberWeeks);//exit;

        $fromWeekIdParts = explode('-', $filter['fromWeekId']);
        $fromYear = $fromWeekIdParts[0];
        $fromWeek = $fromWeekIdParts[1];
        $toWeekIdParts = explode('-', $filter['toWeekId']);
        $toYear = $toWeekIdParts[0];
        $toWeek = $toWeekIdParts[1];
        $filteredStaffMemberData = [];

        $points = null;
        $previousPoints = null;
        foreach ($staffMemberWeeks as $staffMemberWeek) {
            $year = $staffMemberWeek['year'];
            $week = $staffMemberWeek['weekNumber'];
        
            if ($preventDefaultFilters || ($year > $fromYear || ($year == $fromYear && $week >= $fromWeek))) {
                if ($preventDefaultFilters || ($year < $toYear || ($year == $toYear && $week <= $toWeek))) {
                    $staffMemberStat = $this->findStaffMemberStat($year, $week);
                    $points = $staffMemberStat ? $staffMemberStat->getPoints() : null;
                    if ($points === null || $previousPoints === null) {
                        $trend = self::TREND_NEUTRAL;
                    } else {
                        if ($points > $previousPoints) {
                            $trend = self::TREND_INCREASING;
                        } else {
                            $trend = self::TREND_DECREASING;
                        }
                    }
                    $staffMemberWeek['points'] = $points;
                    $staffMemberWeek['staffMemberStatId'] = $staffMemberStat ? $staffMemberStat->getId() : null;
                    $staffMemberWeek['trend'] = $trend;
                    $filteredStaffMemberData[] = $staffMemberWeek;
                }
            }
            $previousPoints = $points;
        }

        // dump($this->staffMember);

        // $filteredStaffMemberData = self::completeArrayLenghtToCategoryValueCount($filteredStaffMemberData);

        // dump($filteredStaffMemberData);exit;

        // $filteredStaffMemberData

        $categories = self::createCategoriesArray($filteredStaffMemberData);

        $result = [
            'seriesData' => self::createSeriesData($filteredStaffMemberData, $categories),
            // 'series' => self::createSeriesArray($filteredStaffMemberData, $categories),
            'categories' => $categories
        ];

        // dump(CustomWeekManager::getWeekId(-1));
        // dump($result);exit;

        return $result;
    }

    // public static function completeArrayLenghtToCategoryValueCount($filteredStaffMemberData)
    // {
    //     if (count($filteredStaffMemberData) < self::CATEGORIES_VALUE_COUNT) {
    //         for ($i = (count($filteredStaffMemberData)); $i < self::CATEGORIES_VALUE_COUNT; $i++) {
    //             $filteredStaffMemberData[] = [
    //                 'year' => null,
    //                 'monthNumber' => null,
    //                 'weekNumber' => null,
    //                 'weekId' => null,
    //                 'weekStartDate' => null,
    //                 'weekEndDate' => null,
    //                 'points' => null,
    //                 'staffMemberStatId' => null,
    //                 'trend' => null
    //             ];
    //         }
    //     }

    //     return $filteredStaffMemberData;
    // }

    public static function createSeriesData($periodData, $categories)
    {
        // dump($periodData);exit;
        $seriesArray = [];
        foreach ($categories as $category) {
            $categoryParts = explode('-', $category);
            $categoryFound = false;
            foreach ($periodData as $periodDataRow) {
                if ($periodDataRow['weekId'] == $category) {
                    $categoryFound = true;
                    $seriesArray[] = $periodDataRow;
                }
                // $points = $periodDataRow['points'] ? : 0;
                // $seriesArray[] = $points;
            }
            if (!$categoryFound) {
                $seriesArray[] = [
                    'year' => $categoryParts[0],
                    'monthNumber' => null,
                    'weekNumber' => (int)$categoryParts[1],
                    'weekId' => $category,
                    'weekStartDate' => null,
                    'weekEndDate' => null,
                    'points' => null,
                    'trend' => self::TREND_NEUTRAL
                ];
            }
        }

        return $seriesArray;
    }

    public static function createCategoriesArray($periodData)
    {
        App::getContainer()->wireService('StatisticsPackage/service/CustomWeekManager');

        $categoriesArray = [];
        $uniqueWeekIds = array();
        foreach ($periodData as $weeklyData) {
            $weekId = $weeklyData['weekId'];
            if (!in_array($weekId, $uniqueWeekIds)) {
                $uniqueWeekIds[] = $weekId;
            }
        }

        // $lastWeekId = $uniqueWeekIds[count($uniqueWeekIds) - 1];
        // dump($lastWeekId);
        // $thisWeekId = 

        /**
         * Elso megoldas: elerakjuk a hianyzo ertekeket
        */
        // if (count($uniqueWeekIds) < self::CATEGORIES_VALUE_COUNT) {
        //     for ($i = 0; $i < (self::CATEGORIES_VALUE_COUNT - count($uniqueWeekIds)) - 1; $i++) {
        //         $categoriesArray[] = null;
        //     }
        //     $categoriesArray = array_merge($categoriesArray, $uniqueWeekIds);
        // } else {
        //     $categoriesArray = $uniqueWeekIds;
        // }

        /**
         * Masodik megoldas: mogerakjuk a hianyzo ertekeket
        */
        if (count($uniqueWeekIds) < self::CATEGORIES_VALUE_COUNT) {
            $lastWeekId = $uniqueWeekIds[count($uniqueWeekIds) - 1];
            $thisWeekId = CustomWeekManager::getWeekId(0);
            // dump($lastWeekId);
            // dump($thisWeekId);
            // $lastWeekId = '2023-26';

            $weekIdDifference = CustomWeekManager::getWeekIdDifference($lastWeekId, $thisWeekId);
            $weekIndex = $weekIdDifference + 1;

            // $diff = CustomWeekManager::getWeekIdDifference('2021-34', '2019-03');
            // dump($diff);exit;

            for ($i = (count($uniqueWeekIds)); $i < self::CATEGORIES_VALUE_COUNT; $i++) {
                $categoriesArray[] = CustomWeekManager::getWeekId($weekIndex);
                $weekIndex++;
            }
            $categoriesArray = array_merge($uniqueWeekIds, $categoriesArray);
        } else {
            $categoriesArray = $uniqueWeekIds;
        }

        // exit;

        return $categoriesArray;
    }

    public function saveStatForWeek($year, $weekNumber, $originalPoints)
    {
        // $success = true;
        $staffMemberStat = null;
        $errorMessage = null;
        $points = (int)$originalPoints;
        if ($originalPoints != $points) {
            $errorMessage = trans('invalid.points.value').' ('.$originalPoints.')';
        }
        if (empty($originalPoints)) {
            // $success = false;
            $errorMessage = trans('empty.points.value');
            // return false;
        }
        if ($points > $this->maxPoints) {
            $errorMessage = trans('points.value.exceeded.the.limit').' ('.$this->maxPoints.')';
        }

        if (!$errorMessage) {
            $staffMemberStat = $this->findStaffMemberStat($year, $weekNumber);
            if (!$staffMemberStat) {
                App::getContainer()->wireService('StaffPackage/entity/StaffMemberStat');
                $staffMemberStat = new StaffMemberStat();
                $staffMemberStat->setYearOfRelevance($year);
                $staffMemberStat->setPeriodSerial($weekNumber);
            }
            $staffMemberStat->setStaffMember($this->staffMember);
            $staffMemberStat->setPoints($points);
            $staffMemberStat = $staffMemberStat->getRepository()->store($staffMemberStat);
        }

        return [
            // 'success' => $success,
            'errorMessage' => $errorMessage,
            'staffMemberStat' => $staffMemberStat
        ];
    }

    public function findStaffMemberStat($year, $week) :? StaffMemberStat
    {
        $statRepo = $this->getStaffMemberStatRepository();
        $staffMemberStat = $statRepo->findOneBy(['conditions' => [
            ['key' => 'staff_member_id', 'value' => $this->staffMember->getId()],
            ['key' => 'year_of_relevance', 'value' => $year],
            ['key' => 'period_serial', 'value' => $week]
        ]]);

        return $staffMemberStat;
    }

    public function getStaffMemberWeeks()
    {
        App::getContainer()->wireService('StatisticsPackage/service/CustomWeekManager');
        $firstDate = $this->staffMember->getTrainedAt() ? : $this->staffMember->getCreatedAt();
        $firstDateTime = new \DateTime($firstDate);
        $staffMemberWeeks = CustomWeekManager::listCustomWeekDates($firstDateTime->format('Y-m-d'));

        return $staffMemberWeeks;
    }

    public static function findInStaffMemberWeeks($staffMemberWeeks, $key, $value)
    {
        foreach ($staffMemberWeeks as $staffMemberWeek) {
            if ($staffMemberWeek[$key] == $value) {
                return $staffMemberWeek;
            }
        }

        return null;
    }
}
