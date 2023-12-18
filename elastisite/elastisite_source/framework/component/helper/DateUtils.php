<?php

namespace framework\component\helper;

use framework\component\exception\ElastiException;

class DateUtils
{
    const DAY_MONDAY = 'monday';
    const DAY_TUESDAY = 'tuesday';
    const DAY_WEDNESDAY = 'wednesday';
    const DAY_THURSDAY = 'thursday';
    const DAY_FRIDAY = 'friday';
    const DAY_SATURDAY = 'saturday';
    const DAY_SUNDAY = 'sunday';

    const NUMBERS = [
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
    ];

    public static function getMonthName($monthNumber)
    {
        $translationKeys = array(
            1 => 'january',
            2 => 'february',
            3 => 'march',
            4 => 'april',
            5 => 'may',
            6 => 'june',
            7 => 'july',
            8 => 'august',
            9 => 'september',
            10 => 'october',
            11 => 'november',
            12 => 'december'
        );
        return trans($translationKeys[(int)$monthNumber]);
    }

    public static function getCurrentYear()
    {
        return date('Y');
    }

    public static function getDay($date)
    {
        return (new \DateTime($date))->format('Y-m-d');
    }

    public static function getCurrentDate($format = 'Y-m-d H:i:s')
    {
        return (new \DateTime())->format($format);
    }

    public static function getDate($date, $format = 'Y-m-d H:i:s')
    {
        return (new \DateTime($date))->format($format);
    }

    public static function getDateObject($date)
    {
        return (new \DateTime($date));
    }

    public static function getDateWithZone()
    {
        $date = new \DateTime();
        
        return $date->format('Y-m-d').'T'.$date->format('H:i:s').'.440Z';
    }

    public static function getPeriodMonthProperties(\DateTime $startDate, \DateTime $endDate) 
    {
        $startYear = (int)$startDate->format('Y');
        $endYear = (int)$endDate->format('Y');
        $monthCounter = 0;
        $rawResult = [];
        for ($yearLoop = $startYear; $yearLoop <= $endYear; $yearLoop++) {
            if ($yearLoop == $startYear) {
                $startMonth = (int)$startDate->format('m');
            } else {
                $startMonth = 1;
            }

            if ($yearLoop == $endYear) {
                $endMonth = (int)$endDate->format('m');
            } else {
                $endMonth = 12;
            }

            for ($monthLoop = $startMonth; $monthLoop <= $endMonth; $monthLoop++) {
                $rawResult[] = [
                    'year' => $yearLoop,
                    'month' => $monthLoop,
                    'monthName' => self::getMonthName($monthLoop),
                    'monthCounter' => $monthCounter
                ];
                $monthCounter++;
            }
        }

        /*
        We need to loop the results again, to get the monthIndexes.
        */
        $result = [];
        $monthIndexDifference = $monthCounter - 1;
        foreach ($rawResult as $index => $rawResultRow) {
            $result[$index] = $rawResultRow;
            $result[$index]['monthIndex'] = $rawResultRow['monthCounter'] - $monthIndexDifference;
        }

        return $result;
    }

    public static function getPeriodDates(int $periodStartIndex = 0, $periodType = 'month', $periods = 1, $format = 'Y-m-d')
    {
        if ($periodStartIndex < 0) {
            $periodStartIndex = (string)$periodStartIndex;
        } else {
            $periodStartIndex = (string)"+".$periodStartIndex;
        }

        $start = new \DateTime(date('Y-m-01').' '.$periodStartIndex.' '.$periodType);

        if ($start->format('Y-m') == date('Y-m')) {
            $end = new \DateTime();
        } else {
            $end = new \DateTime(date("Y-m-t", strtotime($start->format($format))));
        }

        $startDateTime = $start->format($format);
        $endDateTime = $end->format($format);

        if ($startDateTime > $endDateTime) {
            $earlierDateTime = $endDateTime;
            $laterDateTime = $startDateTime;
        } else {
            $earlierDateTime = $startDateTime;
            $laterDateTime = $endDateTime;
        }
        $laterDateTimeObj = new \DateTime($laterDateTime);

        return [
            'start' => $earlierDateTime,
            'end' => $laterDateTimeObj->format($format)
        ];
    }

    public static function getDateParams($date)
    {
        $temp1array = explode(' ', $date);
        $temp2array = explode('-', $temp1array[0]);
        $temp3array = explode(':', $temp1array[1]);
        $dateParams['year'] = $temp2array[0];
        $dateParams['month'] = $temp2array[1];
        $dateParams['day'] = $temp2array[2];
        $dateParams['hour'] = $temp3array[0];
        $dateParams['min'] = $temp3array[1];
        $dateParams['sec'] = $temp3array[2];
        return $dateParams;
    }

    public static function getUnixTimestamp($date)
    {
        $dateParams = self::getDateParams($date);
        return mktime(
            $dateParams['hour'] , $dateParams['min'] ,
            $dateParams['sec'] , $dateParams['month'] ,
            $dateParams['day'] , $dateParams['year']
        );
    }

    public static function isCurrentDateInInterval($startDate, $endDate)
    {
        $now = time();
        if (($startDate < $now) and  ($now < $endDate)) {
            return true;
        } else {
            return false;
        }
    }

    public static function isValidDateFormat($inputString, $dateFormat = 'Y-m-d')
    {
        $dateTime = \DateTime::createFromFormat($dateFormat, $inputString);

        return $dateTime && $dateTime->format($dateFormat) === $inputString;
    }

    public static function isValidTimeFormat($inputString, $timeFormat = 'H:i')
    {
        $dateTime = \DateTime::createFromFormat($timeFormat, $inputString);
    
        return $dateTime && $dateTime->format($timeFormat) === $inputString;
    }

    public static function mendDates($startDate = null, $endDate = null)
    {
        $today = date('Y-m-d');
    
        // Ha a kezdődátum nincs megadva, használjuk az egy évvel korábbi dátumot
        $startDate = $startDate ?? date('Y-m-d', strtotime('-1 year'));
        // Ha a végdátum nincs megadva, használjuk az egy évvel későbbi dátumot
        $endDate = $endDate ?? date('Y-m-d', strtotime('+1 year'));
    
        // Ellenőrzés: Ha a kezdődátum későbbi, mint a végdátum, akkor megcseréljük őket
        if (strtotime($startDate) > strtotime($endDate)) {
            list($startDate, $endDate) = [$endDate, $startDate];
        }
    
        if (strtotime($endDate) < strtotime($today)) {
            throw new \InvalidArgumentException('End date cannot be earlier than today');
        }
    
        return [
            'startDate' => $startDate, 
            'endDate' => $endDate
        ];
    }

    public static function extractDateAndTime(string $dateTime) : array
    {
        $dateTimeObject = new \DateTime($dateTime);

        if (strpos($dateTime, ' ') !== false) {
            $date = $dateTimeObject->format('Y-m-d');
            $time = $dateTimeObject->format('H:i:s');
        } else {
            $date = $dateTimeObject->format('Y-m-d');
            $time = null;
        }

        return [
            'date' => $date,
            'time' => $time
        ];
    }

    public static function getDayOfMonth(string $date) : int
    {
        $dateTimeObj = new \DateTime($date);
        $day = $dateTimeObj->format('d');

        return (int)$day;
    }

    public static function getWeekOfMonthFromDate(string $date) : int
    {
        return (int)self::getWeekOfMonth(self::getDayOfMonth($date));
    }

    public static function getWeekOfMonth($day): int
    {
        return (int)ceil($day / 7);
    }
}
