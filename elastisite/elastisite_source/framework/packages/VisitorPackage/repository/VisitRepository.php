<?php
namespace framework\packages\VisitorPackage\repository;

use App;
use framework\component\parent\DbRepository;
// use framework\kernel\utility\BasicUtils;
use framework\component\helper\DateUtils;

class VisitRepository extends DbRepository
{
    // const STAT_TYPE_30DAYS_0 = 1;
    // const STAT_TYPE_LAST_30 = 1;

    public function __construct()
    {

    }

    public function getEarliestDate()
    {
        $stm = "SELECT v.visited_at
        FROM visit v 
        WHERE website = :website
        ORDER BY v.id ASC LIMIT 1";
        $dbm = $this->getDbManager();
        $params = ['website' => App::getWebsite()];
        // dump($stm);dump($params);exit;
        $result = $dbm->findOne($stm, $params);
        return $result ? $result['visited_at'] : null;
    }

    public function getStatsByDay(int $periodStartIndex = 0, $periodType = 'month', $periods = 1, $format = 'Y-m-d')
    {
        $periodDates = DateUtils::getPeriodDates($periodStartIndex, $periodType, $periods, $format);
        // dump($periodStartIndex);
        //dump($periodDates);//exit;
        $stm = "SELECT count(id) as total_page_loads, count(DISTINCT visitor_code) as total_visitors, DATE_FORMAT(visited_at, \"%Y-%m-%d\") as visited_at
        FROM visit
        WHERE website = :website AND (visited_at BETWEEN :periodStartDate AND :periodEndDate)
        GROUP BY visited_at ";
        $dbm = $this->getDbManager();
// dump($periodDates);
        $stats = [
            'result' => $dbm->findAll($stm, [
                ':website' => App::getWebsite(),
                ':periodStartDate' => $periodDates['start'],
                ':periodEndDate' => $periodDates['end']
            ]),
            'currentMonthName' => DateUtils::getMonthName((new \DateTime($periodDates['start']))->format('m')),
            'periodStartDate' => $periodDates['start'],
            'periodEndDate' => $periodDates['end']
        ];
        return $stats;
    }

    public function getStatsByRoute(int $periodStartIndex = 0, $backInTime = true, $periodType = 'month', $periods = 1, $format = 'Y-m-d')
    {
        $periodDates = DateUtils::getPeriodDates();
        $stm = "SELECT v.route_name , count(id) as total_page_loads, count(DISTINCT visitor_code) as total_visitors
        FROM visit v 
        WHERE website = :website AND (visited_at BETWEEN :periodStartDate AND :periodEndDate)
        GROUP BY v.route_name";
        $dbm = $this->getDbManager();
        $result = $dbm->findAll($stm, [
            ':website' => App::getWebsite(),
            ':periodStartDate' => $periodDates['start'],
            ':periodEndDate' => $periodDates['end']
        ]);
        return $result;
    }

    public function collectRecordData($filter, $queryType = 'result', $forceCollection = false, $debug = false)
    {
        // dump($debug);exit;
        // $debug = 'VisitRepository';
        
        return parent::collectRecordData($filter, $queryType = 'result', $forceCollection, $debug);
    }
}
