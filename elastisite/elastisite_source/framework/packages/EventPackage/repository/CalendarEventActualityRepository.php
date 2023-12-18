<?php
namespace framework\packages\EventPackage\repository;

use framework\component\parent\DbRepository;
use framework\kernel\DbManager\manager\DbManager;
use framework\packages\EventPackage\entity\CalendarCheckEventActuality;
use framework\packages\UserPackage\entity\UserAccount;

class CalendarEventActualityRepository extends DbRepository
{
    // public function findExisting(DbManager $dbm, CalendarCheckEventActuality $calendarCheckEventActuality)
    // {
    //     $calendarEvent = $calendarCheckEventActuality->getCalendarEvent();
        
    //     $stm = "SELECT  
    //         FROM calendar_event_actuality as cal_ev_act 
    //         ";
    // }
}
