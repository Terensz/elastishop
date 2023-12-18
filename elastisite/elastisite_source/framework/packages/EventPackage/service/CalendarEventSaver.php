<?php
namespace framework\packages\EventPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\EventPackage\entity\CalendarEvent;
use framework\packages\EventPackage\repository\CalendarEventRepository;

class CalendarEventSaver extends Service
{
    public static function save($calendarEvent, $params)
    {
        App::getContainer()->wireService('EventPackage/entity/CalendarEvent');

        /**
         * eventType is always required! It comes from the business logic, and formatted for that.
         * Example: AscUnit::DUE_CALENDAR_EVENT_TYPE 
        */
        if (!isset($params['eventType'])) {
            throw new \Exception('Missing param: eventType');
        }

        /**
         * This is an own class constant of the CalendarEvent entity.
         * Example: CalendarEvent::FREQUENCY_TYPE_ONE_TIME, CalendarEvent::FREQUENCY_TYPE_DAILY
        */
        if (!isset($params['frequencyType'])) {
            throw new \Exception('Missing param: frequencyType');
        }

        /**
         * This param comes from the business logic
         * Example: AscUnit::STATUS_ACTIVE
        */
        if (!isset($params['status'])) {
            throw new \Exception('Missing param: status');
        }

        if ($params['frequencyType'] == CalendarEvent::FREQUENCY_TYPE_ONE_TIME) {
            $params['recurrenceDayMon'] = null;
            $params['recurrenceDayTue'] = null;
            $params['recurrenceDayWed'] = null;
            $params['recurrenceDayThu'] = null;
            $params['recurrenceDayFri'] = null;
            $params['recurrenceDaySat'] = null;
            $params['recurrenceDaySun'] = null;
        } else {
            if (!isset($params['recurrenceDayMon'])) {
                $params['recurrenceDayMon'] = null;
            }
            if (!isset($params['recurrenceDayTue'])) {
                $params['recurrenceDayTue'] = null;
            }
            if (!isset($params['recurrenceDayWed'])) {
                $params['recurrenceDayWed'] = null;
            }
            if (!isset($params['recurrenceDayThu'])) {
                $params['recurrenceDayThu'] = null;
            }
            if (!isset($params['recurrenceDayFri'])) {
                $params['recurrenceDayFri'] = null;
            }
            if (!isset($params['recurrenceDaySat'])) {
                $params['recurrenceDaySat'] = null;
            }
            if (!isset($params['recurrenceDaySun'])) {
                $params['recurrenceDaySun'] = null;
            }
        }

        /**
         * If this event is recurring, than 
         * Example: 2
        */
        if (!isset($params['recurrenceInterval'])) {
            $params['recurrenceInterval'] = null;
        }

        if ($params['frequencyType'] === CalendarEvent::FREQUENCY_TYPE_ONE_TIME) {
            $params['recurrenceInterval'] = null;
            $params['recurrenceDayMon'] = null; // Hétfő
            $params['recurrenceDayTue'] = null;
            $params['recurrenceDayWed'] = null;
            $params['recurrenceDayThu'] = null;
            $params['recurrenceDayFri'] = null;
            $params['recurrenceDaySat'] = null;
            $params['recurrenceDaySun'] = null;
        } elseif ($params['frequencyType'] === CalendarEvent::FREQUENCY_TYPE_DAILY) {
            $params['recurrenceInterval'] = 1;
            $params['recurrenceUnit'] = CalendarEvent::RECURRENCE_UNIT_DAY;
            $params['recurrenceDayMon'] = 0; // Hétfő
            $params['recurrenceDayTue'] = 0;
            $params['recurrenceDayWed'] = 0;
            $params['recurrenceDayThu'] = 0;
            $params['recurrenceDayFri'] = 0;
            $params['recurrenceDaySat'] = 0;
            $params['recurrenceDaySun'] = 0;
        } elseif ($params['frequencyType'] === CalendarEvent::FREQUENCY_TYPE_WEEKLY_MONDAY) {
            $params['recurrenceInterval'] = 1;
            $params['recurrenceUnit'] = CalendarEvent::RECURRENCE_UNIT_WEEK;
            $params['recurrenceDayMon'] = 1; // Hétfő
            $params['recurrenceDayTue'] = 0;
            $params['recurrenceDayWed'] = 0;
            $params['recurrenceDayThu'] = 0;
            $params['recurrenceDayFri'] = 0;
            $params['recurrenceDaySat'] = 0;
            $params['recurrenceDaySun'] = 0;
            // A specific day settings (Monday)...
        } elseif ($params['frequencyType'] === CalendarEvent::FREQUENCY_TYPE_MONTHLY_FIRST_MONDAY) {
            $params['recurrenceInterval'] = 1;
            $params['recurrenceUnit'] = CalendarEvent::RECURRENCE_UNIT_MONTH;
            $params['recurrenceDayMon'] = 1; // Hétfő
            $params['recurrenceDayTue'] = 0;
            $params['recurrenceDayWed'] = 0;
            $params['recurrenceDayThu'] = 0;
            $params['recurrenceDayFri'] = 0;
            $params['recurrenceDaySat'] = 0;
            $params['recurrenceDaySun'] = 0;
        } elseif ($params['frequencyType'] === CalendarEvent::FREQUENCY_TYPE_ANNUAL_MAY_1ST) {
            $params['recurrenceInterval'] = 1;
            $params['recurrenceUnit'] = CalendarEvent::RECURRENCE_UNIT_YEAR;
            $params['recurrenceDayMon'] = 0; // Hétfő
            $params['recurrenceDayTue'] = 0;
            $params['recurrenceDayWed'] = 0;
            $params['recurrenceDayThu'] = 0;
            $params['recurrenceDayFri'] = 0;
            $params['recurrenceDaySat'] = 0;
            $params['recurrenceDaySun'] = 0;
            // A specific day settings (May 1st)...
        } elseif ($params['frequencyType'] === CalendarEvent::FREQUENCY_TYPE_WEEKDAYS) {
            $params['recurrenceInterval'] = 1;
            $params['recurrenceUnit'] = CalendarEvent::RECURRENCE_UNIT_WEEK;
            $params['recurrenceDayMon'] = 1; // Hétfő
            $params['recurrenceDayTue'] = 1; // Kedd
            $params['recurrenceDayWed'] = 1; // Szerda
            $params['recurrenceDayThu'] = 1; // Csütörtök
            $params['recurrenceDayFri'] = 1; // Péntek
            $params['recurrenceDaySat'] = 0;
            $params['recurrenceDaySun'] = 0;
            // További beállítások...
        } elseif ($params['frequencyType'] === CalendarEvent::FREQUENCY_TYPE_CUSTOM_RECURRENCE) {
            // Nincs további beállítás, meghagyjuk a kapott értékeket
        }

        if (!isset($params['dueTimeMinutes']) || empty($params['dueTimeMinutes'])) {
            $params['dueTimeMinutes'] = "00";
        } else {
            if (is_numeric($params['dueTimeMinutes'])) {
                $params['dueTimeMinutes'] = str_pad($params['dueTimeMinutes'], 2, '0', STR_PAD_LEFT);
            } else {
                $params['dueTimeMinutes'] = "00";
            }
        }
        // dump($params);exit;

        // Az entitás létrehozása és feltöltése a feldolgozott adatokkal
        $calendarEvent->setEventType($params['eventType']);
        $calendarEvent->setTitle(null);
        $calendarEvent->setFrequencyType($params['frequencyType']);
        $calendarEvent->setStartDate($params['dueDate']); // startDate helyett dueDate
        if ($params['entireDay']) {
            $calendarEvent->setStartTime(null);
        } else {
            $calendarEvent->setStartTime($params['dueTimeHours'] . ':' . $params['dueTimeMinutes']);
        }
        $calendarEvent->setEndDate(null);
        $calendarEvent->setEndTime(null);
        $calendarEvent->setRecurrenceUnit($params['recurrenceUnit']);
        $calendarEvent->setRecurrenceInterval($params['recurrenceInterval']);

        // dump($calendarEvent);exit;

        // További rekurrens napok beállítása
        $calendarEvent->setRecurrenceDayMon($params['recurrenceDayMon']);
        $calendarEvent->setRecurrenceDayTue($params['recurrenceDayTue']);
        $calendarEvent->setRecurrenceDayWed($params['recurrenceDayWed']);
        $calendarEvent->setRecurrenceDayThu($params['recurrenceDayThu']);
        $calendarEvent->setRecurrenceDayFri($params['recurrenceDayFri']);
        $calendarEvent->setRecurrenceDaySat($params['recurrenceDaySat']);
        $calendarEvent->setRecurrenceDaySun($params['recurrenceDaySun']);

        $calendarEvent->setStatus($params['status']);

        if (!empty($params['dueDate'])) {
            // dump($params);exit;
            $calendarEvent = $calendarEvent->getRepository()->store($calendarEvent);
        } else {
            return null;
        }

        // dump('Miaf.');exit;
        // dump($params);exit;

        return $calendarEvent;
    }
}
