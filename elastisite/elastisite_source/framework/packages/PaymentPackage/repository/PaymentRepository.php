<?php
namespace framework\packages\PaymentPackage\repository;

use framework\component\helper\DateUtils;
use framework\component\parent\DbRepository;
use framework\packages\PaymentPackage\entity\Payment;

class PaymentRepository extends DbRepository
{
    public function getEarliestDate()
    {
        $stm = "SELECT a.created_at
        FROM payment a 
        ORDER BY a.id ASC LIMIT 1";
        $dbm = $this->getDbManager();
        $result = $dbm->findOne($stm, []);

        return $result ? $result['created_at'] : null;
    }

    /**
     * @todo
     * This query does not support Postgres yet.
    */
    public function getStatsByDay(int $periodStartIndex = 0, $periodType = 'month', $periods = 1, $format = 'Y-m-d')
    {
        $this->getContainer()->wireService('PaymentPackage/entity/Payment');
        $periodDates = DateUtils::getPeriodDates($periodStartIndex, $periodType, $periods, $format);
        $periodEndDateObject = new \DateTime($periodDates['end']);
        $periodEndDateObject->modify('+1 day');
        $periodEndDate = $periodEndDateObject->format('Y-m-d');
        // dump($periodStartIndex);
        //dump($periodDates);//exit;
        $stm = "SELECT count(DISTINCT id) as total_payment_occasions, sum(total_gross_value) as gross_income, DATE_FORMAT(created_at, \"%Y-%m-%d\") as create_day
        FROM payment a
        WHERE status = :status_succeeded AND (created_at BETWEEN :periodStartDate AND :periodEndDate)
        GROUP BY create_day 
        ORDER BY create_day ASC ";
        $dbm = $this->getDbManager();
// dump($periodDates);exit;
        $stats = [
            'result' => $dbm->findAll($stm, [
                ':status_succeeded' => Payment::PAYMENT_STATUS_SUCCEEDED,
                ':periodStartDate' => $periodDates['start'],
                ':periodEndDate' => $periodEndDate
            ]),
            'currentMonthName' => DateUtils::getMonthName((new \DateTime($periodDates['start']))->format('m')),
            'periodStartDate' => $periodDates['start'],
            'periodEndDate' => $periodEndDate
        ];
        // dump($stats);exit;

        return $stats;
    }
}
