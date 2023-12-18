<?php
namespace framework\packages\StatisticsPackage\service\ApexCharts;

use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;

class ChartDataService extends Kernel
{
    /**
     * Category: Az az adattipus, aminek a fuggvenyeben kirajzoltatjuk az adatokat. Ez a legtobb esetben idoszak napjai vagy honapjai.
     * Series: Osszetartozo adatok sokasaga
     * Component: a Series lebontva tenyezokre. A stacked barchart-nal van jelentosege.
    */

    public static function createData(array $rawData, array $valueAxisFieldNames, string $categoryAxisFieldName = 'created_at', array $seriesTitles = [], $startDate = null, $endDate = null)
    {
        // dump($rawData);
        // if (empty($rawData)) {
        //     return;
        // }
        // $valueAxisFieldNames = [];
        // if (is_array($rawData) && isset($rawData[0])) {
        //     foreach ($rawData[0] as $fieldName => $value) {
        //         if ($fieldName != $categoryAxisFieldName) {
        //             $valueAxisFieldNames[] = $fieldName;
        //         }
        //     }
        // }

        /**
         * Ha nincs megadva a kezdo vagy a vegdatum, akkor a hianyzot kitalaljuk a megadott adatokbol.
        */
        if (!$startDate || !$endDate) {
            $earliestDate = null;
            $latestDate = null;
            foreach ($rawData as $rawDataRow) {
                $date = $rawDataRow[$categoryAxisFieldName];
                if (!$startDate) {
                    if (!$earliestDate || $earliestDate > $date) {
                        $earliestDate = $date;
                    }
                }
                if (!$endDate) {
                    if (!$endDate || $endDate < $date) {
                        $latestDate = $date;
                    }
                }
                // $rawCategoryValues[] = $rawDataRow[$categoryAxisFieldName];
            }
            $startDate = $earliestDate;
            $endDate = $latestDate;
        }

        /**
         * Most mar megvan a kezdo es vegdatumunk. De lehetnek kozte lyukak, hiszen nem biztos, hogy minden nap volt adatunk, ezert a createDaysOfPeriod() metodussal "betomjuk" a lyukakat.
        */
        $startDateTime = new \DateTime($startDate);
        $endDateTime = new \DateTime($endDate);
        $categoryValues = self::createDaysOfPeriod($startDateTime->format('Y'), $startDateTime->format('z'), $endDateTime->format('Y'), $endDateTime->format('z'), false);

        /**
         * Ujraindexeljuk a rawData tombunket a kategoriakkal (datumokkal).
        */
        $categorizedRawData = [];
        foreach ($rawData as $rawDataRow) {
            $categorizedRawData[$rawDataRow[$categoryAxisFieldName]] = $rawDataRow;
        }

        /**
         * Es vegul osszerakjuk a series tombunket.
        */
        $series = [];
        foreach ($valueAxisFieldNames as $seriesIndex => $valueAxisFieldName) {
            $seriesData = [];
            foreach ($categoryValues as $categoryValue) {
                $seriesData[] = isset($categorizedRawData[$categoryValue]) ? $categorizedRawData[$categoryValue][$valueAxisFieldName] : 0;
            }
            $series[$seriesIndex] = [
                'name' => trans(str_replace('_', '.', isset($seriesTitles[$seriesIndex]) ? $seriesTitles[$seriesIndex] : $valueAxisFieldName)),
                'data' => $seriesData
            ];
        }

        // if (empty($rawData)) {
        //     foreach ($rawData as $rawDataRow) {

        //     }
        // }

        array_walk($categoryValues, function(&$x) {$x = "'$x'";});
        // dump($categoryValues);exit;
        return [
            'series' => $series,
            'categoryValues' => $categoryValues
        ];
    }

    /**
     * Category: the timeline axis
     */
    public static function categorizeDetails(array $details, array $categories) : array
    {
        $categorizedDetails = [];
        foreach ($details as $detailTimeUnitId => $detailArray) {
            foreach ($categories as $dataPointIndex => $timeUnitId) {
                if ($detailTimeUnitId == $timeUnitId) {
                    $categorizedDetails[$dataPointIndex] = $detailArray;
                }
            }
        }
        return $categorizedDetails;
    }

    /**
     * ApexCharts categories should be a normal serial array. But when we assemble the output, its an associative array. This method does the conversion
     */
    public static function toSerialArray(array $assocArray) : array
    {
        $res = [];
        foreach ($assocArray as $value) {
            $res[] = $value;
        }
        return $res;
    }

    /**
     * ApexCharts waits stacked bar data in a shuffled form, but no worries, this method makes the conversion.
     * ----
     * @var rawData: The raw array. They key of $rawData can be anything, but $titleIndex can only be that serial number what is the key of the @var $titles.
     * 
     * Example:
     * array:94 [
     * 0 => array:3 [
     *   0 => 1905000.0 // This key also the key of the @var $titles. And its value is also the value of the stacked bar's same key's component.
     *   1 => 698500.0
     *   2 => 0
     *  ]
     *  ...
     * ]
     * @var titles: The array of the stacked bar's components. Its key is also that serial, which rawdata's value array is indexed with. And it's value is the name of the component.
     * 
     */
    public static function convertDataToMultiSeries(array $rawData, array $titles) : array
    {
        $res = [];
        foreach ($rawData as $row) {
            foreach ($row as $titleIndex => $value) {
                if (!isset($res[$titleIndex]['name'])) {
                    $res[$titleIndex]['name'] = $titles[$titleIndex];
                }
                if (!isset($res[$titleIndex]['data'])) {
                    $res[$titleIndex]['data'] = [];
                }
                $res[$titleIndex]['data'][] = $value;
            }
        }
        return $res;
    }

    /**
     * TimeUnitID: year-dayOfYear. E.g.: 2021-314. This method makes a plain date from this TimeUnitID format
    */
    public static function timeUnitIdToDate($timeUnitId)
    {
        $timeUnitIdParts = explode('-', $timeUnitId);
        $date = \DateTime::createFromFormat( 'Y z' , $timeUnitIdParts[0].' '.$timeUnitIdParts[1]);
        return $date->format('Y-m-d');
    }

    /**
     * This method is good for putting the new value in the appropriate location for the component into a "stacked bar" 
     * series array (values depending on components and categories).
     * Now then: if a new value (e.g., cash flow) arrives in the same category (e.g., date), it will be loaded into the 
     * array for the appropriate component, and the method will return with this.
    */
    public static function fillMultiSeriesComponentData(string $value, array $componentIds, string $componentId, array $valueArray = null) : array
    {
        // dump($valueArray);
        $res = [];
        for ($i = 0; $i < count($componentIds); $i++) {
            if (!isset($valueArray[$i])) {
                $valueArray[$i] = 0;
            }

            if ($componentIds[$i] == $componentId) {
                $value += $valueArray[$i];
                array_push($res, $value);
            } else {
                array_push($res, $valueArray[$i]);
            }
        }
        return $res;
    }

    /**
     * This method fills all the missing dates of the entire timeline with 0 datas. It's used by stacked bars.
    */
    public static function createMissingMultiSeriesDailyData(string $beginningYear, string $beginningDay, string $endingYear, string $endingDay, array $componentIds) : array
    {
        $result = [];
        for ($i = $beginningYear; $i <= $endingYear; $i++) {
            $leap = date('L', mktime(0, 0, 0, 1, 1, $i));
            $lastDayOfYear = $leap ? 365 : 364;
            /**
             * First loop runs into this branch. If no other years, it wont go into any other year branches.
            */
            if ($i == $beginningYear) {
                /**
                 * If end date is also in this year, then it will just count until this, otherwise until the last day of the year.
                */
                $afterLastDay = $endingYear == $i ? $endingDay + 1 : ($lastDayOfYear + 1);
                for ($tui = (int)$beginningDay + 1; $tui < $afterLastDay; $tui++) {
                    $result[$i.'-'.$tui] = self::fillMultiSeriesComponentData(0, $componentIds, 0, null);
                }
            }
            /**
             * Runs into this branch only in the last loop, but only when the entire period lasts for more years.
            */
            elseif ($i != $beginningYear && $i == $endingYear) {
                for ($tui = 0; $tui < $endingDay; $tui++) {
                    $result[$i.'-'.$tui] = self::fillMultiSeriesComponentData(0, $componentIds, 0, null);
                }
            }
            /**
             * Runs into this branch only if the period lasts more than 2 years, and only in those loops when we fill the entire year.
            */
            else {
                for ($tui = 0; $tui < ($lastDayOfYear + 1); $tui++) {
                    $result[$i.'-'.$tui] = self::fillMultiSeriesComponentData(0, $componentIds, 0, null);
                }
            }
        }
        return $result;
    }

    public static function createDaysOfPeriod(string $beginningYear, string $beginningDay, string $endingYear, string $endingDay, $inverseReturnArray = true) : array
    {
        // dump($beginningYear.'-'.$beginningDay);
        $result = [];
        $inverseResult = [];
        for ($i = $beginningYear; $i <= $endingYear; $i++) {
            // dump('alma');
            $leap = date('L', mktime(0, 0, 0, 1, 1, $i));
            $lastDayOfYear = $leap ? 365 : 364;
            /**
             * First loop runs into this branch. If no other years, it wont go into any other year branches.
            */
            if ($i == $beginningYear) {
                /**
                 * If end date is also in this year, then it will just count until this, otherwise until the last day of the year.
                */
                $afterLastDay = $endingYear == $i ? $endingDay + 1 : ($lastDayOfYear + 1);
                for ($tui = (int)$beginningDay; $tui < $afterLastDay; $tui++) {
                    $date = self::timeUnitIdToDate($i.'-'.$tui);
                    $inverseResult[$date] = count($result);
                    $result[] = $date;
                }
            }
            /**
             * Runs into this branch only in the last loop, but only when the entire period lasts for more years.
            */
            elseif ($i != $beginningYear && $i == $endingYear) {
                for ($tui = 0; $tui <= $endingDay; $tui++) {
                    $date = self::timeUnitIdToDate($i.'-'.$tui);
                    $inverseResult[$date] = count($result);
                    $result[] = $date;
                }
            }
            /**
             * Runs into this branch only if the period lasts more than 2 years, and only in those loops when we fill the entire year.
            */
            else {
                for ($tui = 0; $tui < $lastDayOfYear; $tui++) {
                    $date = self::timeUnitIdToDate($i.'-'.$tui);
                    $inverseResult[$date] = count($result);
                    $result[] = $date;
                }
            }
        }

        return $inverseReturnArray ? $inverseResult : $result;
    }
}
