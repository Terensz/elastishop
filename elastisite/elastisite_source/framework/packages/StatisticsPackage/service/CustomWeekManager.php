<?php
namespace framework\packages\StatisticsPackage\service;

use DateInterval;
use DateTime;
use framework\kernel\component\Kernel;

class CustomWeekManager extends Kernel
{
    const WEEK_START_DAY = 4;
    const WEEK_END_TIME = '14:00';

    public static function getWeekNumber($date)
    {
        $week = date('W', strtotime($date));
        $dayOfWeek = date('N', strtotime($date));
        
        if ($dayOfWeek >= self::WEEK_START_DAY) {
            return str_pad((int)$week, 2, '0', STR_PAD_LEFT); // Formázzuk a hét számát mindig két karakter hosszú sztringgé
        } else {
            $prevWeek = $week - 1;
            return str_pad((int)$prevWeek, 2, '0', STR_PAD_LEFT); // Formázzuk a hét számát mindig két karakter hosszú sztringgé
        }
    }

    public static function getWeekDates($weekSerial, $year)
    {
        $weekStartDay = self::WEEK_START_DAY;
        $weekEndHour = explode(':', self::WEEK_END_TIME)[0];
        $weekEndMinute = explode(':', self::WEEK_END_TIME)[1];
    
        $startDateTime = new DateTime();
        $startDateTime->setISODate($year, $weekSerial, $weekStartDay);
        $startDateTime->setTime($weekEndHour, $weekEndMinute, 0);
    
        $endDateTime = clone $startDateTime;
        $endDateTime->add(new DateInterval('P7D'));
        $endDateTime->setTime($weekEndHour, $weekEndMinute, 0);
    
        return array(
            'weekStartDate' => $startDateTime->format('Y-m-d H:i:s'),
            'weekEndDate' => $endDateTime->format('Y-m-d H:i:s')
        );
    }

    /**
     * Lists all custom weeks from the @var $date until the current date.
    */
    public static function listCustomWeekDates($date)
    {
        $currentYear = date('Y', strtotime($date));
        $currentWeek = self::getWeekNumber($date);
        $currentWeekDates = self::getWeekDates($currentWeek, $currentYear);
    
        $today = new DateTime();
        $today->setTime(0, 0, 0);
        $customWeeks = [];
    
        while ($currentWeekDates['weekEndDate'] <= $today->format('Y-m-d H:i:s')) {
            $startDateParts = explode('-', $currentWeekDates['weekStartDate']);
            $startYear = $startDateParts[0];
            $weekNumber = self::getWeekNumber($currentWeekDates['weekStartDate']);
            $monthNumber = self::getMonthNumber($weekNumber);
            $year = $startYear;
            $customWeeks[] = [
                'year' => $year,
                'monthNumber' => $monthNumber,
                'weekNumber' => $weekNumber,
                'weekId' => $year . '-' . $weekNumber,
                'weekStartDate' => $currentWeekDates['weekStartDate'],
                'weekEndDate' => $currentWeekDates['weekEndDate']
            ];
    
            $currentWeek++;
            if ($currentWeek > 52) {
                $currentWeek = 1;
                $currentYear++;
            }
    
            $currentWeekDates = self::getWeekDates($currentWeek, $currentYear);
        }
    
        // Hozzaadja a jelenlegi hetet
        $currentWeekDates = self::getWeekDates($currentWeek, $currentYear);
        $customWeeks[] = [
            'year' => $currentYear,
            'monthNumber' => self::getMonthNumber($currentWeek),
            'weekNumber' => $currentWeek,
            'weekId' => $currentYear . '-' . $currentWeek,
            'weekStartDate' => $currentWeekDates['weekStartDate'],
            'weekEndDate' => $currentWeekDates['weekEndDate']
        ];
    
        return $customWeeks;
    }

    public static function getMonthNumber($weekNumber)
    {
        return floor((($weekNumber / 4) - 0.25) + 1);
    }

    public static function getWeekId($index)
    {
        $today = new DateTime();
        $weekNumber = self::getWeekNumber($today->format('Y-m-d'));
        $year = intval($today->format('Y'));

        $newWeekNumber = $weekNumber + $index;
        if ($newWeekNumber <= 0) {
            $year--;
            $newWeekNumber = 52 - abs($newWeekNumber);
        } elseif ($newWeekNumber > 52) {
            $year++;
            $newWeekNumber = $newWeekNumber - 52;
        }

        // Formázzuk a hét számát mindig két karakter hosszú sztringgé
        $formattedWeekNumber = str_pad($newWeekNumber, 2, '0', STR_PAD_LEFT);

        return $year . '-' . $formattedWeekNumber;
    }

    public static function getWeekIdDifference($weekId1, $weekId2)
    {
        $weekId1Parts = explode('-', $weekId1);
        $weekId2Parts = explode('-', $weekId2);
        
        $year1 = intval($weekId1Parts[0]);
        $year2 = intval($weekId2Parts[0]);
        
        $weekNumber1 = intval($weekId1Parts[1]);
        $weekNumber2 = intval($weekId2Parts[1]);
    
        $weeksInYear = 52; // Vagy pontosabb esetben lehetne számolni pl. a date("W", strtotime("{$year1}-12-31")) segítségével.
    
        // Kiszámítjuk az eltelt teljes hetek számát
        $totalWeeksDifference = ($year2 - $year1) * $weeksInYear + ($weekNumber2 - $weekNumber1);
    
        return $totalWeeksDifference;
    }
}

    // public static function getWeekId($index)
    // {
    //     $today = new DateTime();
    //     $weekNumber = self::getWeekNumber($today->format('Y-m-d'));
    //     $year = intval($today->format('Y'));
    
    //     $newWeekNumber = $weekNumber + $index;
    //     if ($newWeekNumber <= 0) {
    //         $year--;
    //         $newWeekNumber = 52 - abs($newWeekNumber);
    //     } elseif ($newWeekNumber > 52) {
    //         $year++;
    //         $newWeekNumber = $newWeekNumber - 52;
    //     }
    
    //     return $year . '-' . (int)$newWeekNumber;
    // }