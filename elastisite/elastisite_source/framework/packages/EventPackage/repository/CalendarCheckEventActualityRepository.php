<?php
namespace framework\packages\EventPackage\repository;

use framework\component\parent\DbRepository;
use framework\kernel\DbManager\manager\DbManager;
use framework\packages\EventPackage\entity\CalendarCheckEventActuality;

class CalendarCheckEventActualityRepository extends DbRepository
{
    // public function findCheckedEvent_OLD(DbManager $dbm, int $calendarEventId, string $runDate)
    // {
    //     $stm = "SELECT 
    //         cal_ch.id 
    //     FROM calendar_event as cal_ev 
    //     JOIN calendar_check_event as cal_ch_ev ON cal_ch_ev.calendar_event_id = cal_ev.id 
    //     JOIN calendar_check as cal_ch ON cal_ch.id = cal_ch_ev.calendar_check_id 
    //     WHERE cal_ev.id = :calendar_event_id 
    //     AND cal_ch.run_date = :cal_ch_run_date
    //     ";
    //     $params = [
    //         'calendar_event_id' => $calendarEventId,
    //         'cal_ch_run_date' => $runDate,
    //     ];
    //     $foundCheckedEvent = $dbm->findOne($stm, $params);

    //     return $foundCheckedEvent;
    // }

    public function findByEventAndDate(DbManager $dbm, int $calendarEventId, string $runDate) : ? CalendarCheckEventActuality
    {
        // dump($runDate);
        $stm = "SELECT 
            cal_ch_ev_act.id as calendar_check_event_actuality_id
        FROM calendar_check_event_actuality as cal_ch_ev_act 
        LEFT JOIN calendar_check as cal_ch ON cal_ch.id = cal_ch_ev_act.calendar_check_id 
        LEFT JOIN calendar_event_actuality as cal_ev_act ON cal_ev_act.id = cal_ch_ev_act.calendar_event_actuality_id 
        LEFT JOIN calendar_event as cal_ev ON cal_ev.id = cal_ev_act.calendar_event_id 
        WHERE cal_ev.id = :calendar_event_id 
        AND cal_ch.run_date = :cal_ch_run_date
        ";

        $params = [
            'calendar_event_id' => $calendarEventId,
            'cal_ch_run_date' => $runDate,
        ];

        $foundCalendarCheckEventActuality = $dbm->findOne($stm, $params);
        // dump($foundCalendarCheckEventActuality);
        // dump($stm);
        // dump($params);
        $object = null;
        if ($foundCalendarCheckEventActuality) {
            $object = $this->find($foundCalendarCheckEventActuality['calendar_check_event_actuality_id']);
        }

        return $object;
    }
}
