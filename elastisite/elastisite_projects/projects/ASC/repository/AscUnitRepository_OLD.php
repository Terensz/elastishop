<?php

namespace projects\ASC\repository;

use App;
use framework\component\parent\DbRepository;
use framework\packages\EventPackage\entity\CalendarEvent;
use framework\packages\StatisticsPackage\service\CustomWeekManager;
use framework\packages\UserPackage\entity\UserAccount;
use projects\ASC\entity\AscScale;
use projects\ASC\entity\AscUnit;
use projects\ASC\service\AscPermissionService;
use projects\ASC\service\AscCalendarEventService;

class AscUnitRepository extends DbRepository
{
    const MOVE_TO_POSITION_AHEAD = 'ahead';
    const MOVE_TO_POSITION_BEHIND = 'behind';

    const ALL_PARENTS = '-all-';

    public function store($entity)
    {
        $entity->setModifiedAt($entity->getCurrentTimestamp());

        $dbm = App::getContainer()->getDbManager();
        $stm = "UPDATE asc_unit SET responsible = :responsible WHERE id = :id ";

        $params = [
            'responsible' => $entity->getResponsible() ? $entity->getResponsible()->getId() : null,
            'id' => $entity->getId()
        ];

        $res = $dbm->execute($stm, $params, true);

        // dump($res);exit;

        // if (empty($entity->getSubject())) {
        //     // throw new ElastiException('Nincs subject!!!!');
        //     return false;
        //     // dump('Nincs subject!!!!');
        //     // echo '<pre>';
        //     // var_dump($entity); exit;
        // }

        return parent::store($entity);
    }

    public function isDeletable($id)
    {
        return self::isParent($id) ? false : true;
    }

    public static function isParent($id)
    {
        $dbm = App::getContainer()->getDbManager();
        $stm = "SELECT count(u.id) as units_count
        FROM asc_unit u 
        WHERE u.parent_id = :parent_id ";

        $params = [
            'parent_id' => $id
        ];

        $result = $dbm->findOne($stm, $params)['units_count'];

        // dump($id);
        // dump($result);

        return $result == 0 ? false : true;
    }

    public function remove($id)
    {
        App::getContainer()->wireService('projects/ASC/service/AscPermissionService');
        $ascUnit = $this->find($id);
        if (!$ascUnit) {
            return false;
        }
        if (!AscPermissionService::checkScalePermission($ascUnit->getAscScale(), AscPermissionService::PERMISSION_TYPE_VIEW)) {
            return false;
        }

        // dump($ascUnit);exit;
        
        $dbm = $this->getDbManager();
        $stm = "DELETE FROM asc_unit WHERE id = :id ";

        $result = $dbm->execute($stm, ['id' => $id]);

        return $result;
    }

    public function getUnitData($ascScaleId, $subject, $position = null)
    {
        $dbm = $this->getDbManager();
        $stm = "SELECT 
            a.id as 'asc_scale_id', 
            b.id as 'asc_unit_id'
        FROM asc_scale a
        INNER JOIN asc_unit b ON b.asc_scale_id = a.id 
        WHERE a.id = :asc_scale_id 
        AND b.subject = :subject
        ".($position ? " AND b.position = :position " : "")."
        ";
        $params = [
            'asc_scale_id' => $ascScaleId,
            'subject' => $subject
        ];

        if ($position) {
            $params['position'] = $position;
        }

        $stm .= " ORDER BY b.sequence_number ASC, b.id ASC ";

        $result = $dbm->findAll($stm, $params);

        return $result;
    }

    public function arrangeSequence(int $scaleId, string $subject, $parentId = self::ALL_PARENTS)
    {
        // if ($parentId != null && !is_string($parentId) && !is_numeric($parentId)) {
        //     dump('Miaf????');
        //     dump($parentId);exit;
        // }
        $stm = "SELECT u.id as asc_unit_id, u.sequence_number
        FROM asc_unit u 
        WHERE asc_scale_id = :asc_scale_id 
        ".($parentId == null ? "AND parent_id IS NULL " : ($parentId != self::ALL_PARENTS ? "" : "AND parent_id = :parent_id "))."
        AND subject = :subject ";

        $params = [
            'asc_scale_id' => $scaleId,
            'subject' => $subject
        ];

        // $parentId = null;
        /**
         * Terence: egyelore megnezzuk igy: $parentId = null;
         * Masnap szinten Terence: megsem.
        */
        if ($parentId != null && $parentId != self::ALL_PARENTS) {
            $stm .= " AND parent_id = :parent_id ";
            $params['parent_id'] = $parentId;
        }

        $stm .= " ORDER BY u.sequence_number ASC, u.id ASC ";

        $dbm = $this->getDbManager();
        $sequenceArray = $dbm->findAll($stm, $params);
        $arrangedArray = self::arrangeSequenceArray($sequenceArray);

        // dump($parentId);
        // dump($stm);
        // dump($params);
        // dump($arrangedArray);//exit;

        foreach ($arrangedArray as $arrangedArrayRow) {
            if ($arrangedArrayRow['oldSequenceNumber'] != $arrangedArrayRow['newSequenceNumber']) {
                $this->updateSequence($arrangedArrayRow['id'], $arrangedArrayRow['newSequenceNumber']);
            }
        }

        // $sequenceArray = $dbm->findAll($stm, $params);
        // dump($sequenceArray);//exit;

        // dump('alma');exit;

        return true;
    }

    /**
     * Mivel a sortable JS hektikusan mukodik, es a kivant elemnek vagy ele vagy moge rakja, igy kenytelenek vagyunk ezt valtozokent kezelni.
    */
    public function wedgeUnitTo(int $scaleId, string $toSubject, AscUnit $movedAscUnit, AscUnit $toUnit, string $aheadOrBehind)
    {
        $stm = "SELECT u.id as asc_unit_id, u.sequence_number
        FROM asc_unit u 
        WHERE asc_scale_id = :asc_scale_id 
        ".($toUnit->getParent() == null ? "AND parent_id IS NULL " : "AND parent_id = :parent_id ")."
        AND subject = :subject 
        ORDER BY u.sequence_number ASC, u.id ASC ";

        // dump('To unit: '.$toUnitId);
        // dump('Moved unit: '.$movedAscUnit->getId());
        // dump('Moved seq: '.$movedAscUnit->getSequenceNumber());//exit;
        $params = [
            'asc_scale_id' => $scaleId,
            'subject' => $toSubject
        ];

        if ($toUnit->getParent()) {
            $params['parent_id'] = $toUnit->getParent()->getId();
        }

        $dbm = $this->getDbManager();
        $sequenceArray = $dbm->findAll($stm, $params);

        // dump($sequenceArray);exit;

        // dump('scaleId: '.$scaleId);
        // dump('toSubject: '.$toSubject);
        // dump('movedAscUnitId: '.$movedAscUnit->getId());
        // dump('toUnitId: '.$toUnitId);
        // dump('aheadOrBehind: '.$aheadOrBehind);
        // dump($sequenceArray);

        // $foundSequence = null;
        // dump('wedge');

        $toUnitFound = false;
        foreach ($sequenceArray as $sequenceArrayRow) {
            if ($sequenceArrayRow['asc_unit_id'] == $toUnit->getId() || $toUnitFound) {
                // dump('megvan a to unit');
                /**
                 * Itt ekeljuk be az uj elemet.
                 * Ha megtalaltuk azt az elemet, ami moge akarjuk tenni, 
                 * akkor eloszor is megupdateljuk az az uj elem erteket a megtalalt erteke + 1-re.
                */
                if (!$toUnitFound) {
                    $movedAscUnit->setSubject($toSubject);
                    // dump($movedAscUnit);exit;
                    $this->store($movedAscUnit);
                    $this->updateSequence($movedAscUnit->getId(), $sequenceArrayRow['sequence_number'] + ($aheadOrBehind == self::MOVE_TO_POSITION_BEHIND ? 1 : 0));
                    $toUnitFound = true;
                    /**
                     * Ha ele akarjuk tenni, akkor a talalt elem erteket is megnoveljuk.
                    */
                    if ($aheadOrBehind == self::MOVE_TO_POSITION_AHEAD) {
                        $this->updateSequence($sequenceArrayRow['asc_unit_id'], $sequenceArrayRow['sequence_number'] + 1);
                    }
                } else {
                    /**
                     * Utana pedig a talat elemet es a tobbiet is 1-gyel nagyobbra.
                     * (Ez a resz minden tovabbi elem loopjanal le fog futni.)
                    */
                    $this->updateSequence($sequenceArrayRow['asc_unit_id'], $sequenceArrayRow['sequence_number'] + 1);
                }
            }
        }


        // dump('Vedd ki!');exit;



        return $movedAscUnit;
    }

    private static function arrangeSequenceArray(array $sequenceArray)
    {
        $nullsArray = [];
        $arrangedArray = [];
        $counter = 1;

        foreach ($sequenceArray as $sequenceArrayRow) {
            if ($sequenceArrayRow['sequence_number'] === null) {
                $nullsArray[] = $sequenceArrayRow;
            } else {
                $arrangedArray[] = [
                    'id' => $sequenceArrayRow['asc_unit_id'],
                    'oldSequenceNumber' => $sequenceArrayRow['sequence_number'],
                    'newSequenceNumber' => $counter
                ];
                $counter++;
            }
        }

        $counter = count($arrangedArray) + 1;
        foreach ($nullsArray as $nullsArrayRow) {
            $arrangedArray[] = [
                'id' => $nullsArrayRow['asc_unit_id'],
                'oldSequenceNumber' => $nullsArrayRow['sequence_number'],
                'newSequenceNumber' => $counter
            ];
            $counter++;
        }

        return $arrangedArray;
    }

    public function updateSequence(int $id, int $newSequence)
    {
        $stm = "UPDATE asc_unit SET sequence_number = :sequence_number WHERE id = :id ";
        $dbm = $this->getDbManager();
        $dbm->execute($stm, [
            'sequence_number' => $newSequence,
            'id' => $id
        ]);
        // dump([
        //     'sequence_number' => $newSequence,
        //     'id' => $id
        // ]);
        // dump($stm);exit;
    }

    public function getNextSequence(int $scaleId, string $subject, $parentId = null) : int
    {
        $stm = "SELECT MAX(sequence_number) as max_sequence_number FROM asc_unit WHERE asc_scale_id = :asc_scale_id AND subject = :subject ";

        $params = [
            'asc_scale_id' => $scaleId,
            'subject' => $subject
        ];

        if ($parentId) {
            $stm .= " AND parent_id = :parent_id ";
            $params['parent_id'] = $parentId;
        }

        $dbm = $this->getDbManager();
        $found = $dbm->findOne($stm, $params);

        if (isset($found['max_sequence_number'])) {
            if ($found['max_sequence_number'] === null || !is_numeric($found['max_sequence_number'])) {
                return 1;
            }
        }

        // if (!isset($found['max_sequence_number'])) {
        //     dump($found);
        // }

        return $found['max_sequence_number'] + 1;
    }

    /**
     * CalendarEvents
    */

    public static function getOneTimeCalendarEventData(UserAccount $userAccount = null, AscScale $ascScale = null, \DateTime $requestedDateObject = null)
    {
        $todayDateObject = new \DateTime();
        if ($requestedDateObject === null || $requestedDateObject > $todayDateObject) {
            $requestedDateObject = $todayDateObject;
        }
        $todayDate = $todayDateObject->format('Y-m-d');
        $requestedDate = $requestedDateObject->format('Y-m-d');

        App::getContainer()->wireService('EventPackage/entity/CalendarEvent');
        $additionalParameters = [
            ['refKey' => 'ce.start_date', 'paramKey' => 'ce_start_date', 'operator' => ($todayDate == $requestedDate ? '=' : '<='), 'value' => $requestedDate],
            ['refKey' => 'au.status', 'paramKey' => 'status', 'value' => CalendarEvent::STATUS_ACTIVE]
        ];
        $data = self::findScaleCalendarEventData($userAccount, $ascScale, $additionalParameters);

        return $data;
    }

    public static function getRecurringCalendarEventData(UserAccount $userAccount = null, AscScale $ascScale = null, \DateTime $requestedDateObject = null, $status = CalendarEvent::STATUS_ACTIVE)
    {
        $todayDateObject = new \DateTime();
        if ($requestedDateObject === null || $requestedDateObject > $todayDateObject) {
            $requestedDateObject = $todayDateObject;
        }
        $todayDate = $todayDateObject->format('Y-m-d');
        $requestedDate = $requestedDateObject->format('Y-m-d');

        $additionalParameters = [
            ['refKey' => 'au.status', 'paramKey' => 'status', 'value' => CalendarEvent::STATUS_ACTIVE]
        ];
    
        App::getContainer()->wireService('StatisticsPackage/service/CustomWeekManager');
        $customWeeks = CustomWeekManager::listCustomWeekDates($requestedDate);
        $resultData = [];
    
        foreach ($customWeeks as $customWeek) {
            $weekStartDate = $customWeek['weekStartDate'];
            $weekEndDate = $customWeek['weekEndDate'];
    
            $additionalParameters[] = ['refKey' => 'ce.start_date', 'paramKey' => 'ce_start_date', 'operator' => ($todayDate == $requestedDate ? '=' : '<='), 'value' => $weekStartDate];
    
            $data = self::findScaleCalendarEventData($userAccount, $ascScale, $additionalParameters);
    
            foreach ($data as $event) {
                $eventStartDate = new \DateTime($event['ce_start_date']);
                $eventStartDate->setTime(0, 0, 0);
                $eventEndDate = new \DateTime($event['ce_start_date']);
                $eventEndDate->setTime(23, 59, 59);
    
                if ($eventStartDate >= $weekStartDate && $eventEndDate <= $weekEndDate) {
                    $resultData[] = $event;
                }
            }
        }
    
        return $resultData;
    }

    public static function getExpiredOneTimeCalendarEventData(UserAccount $userAccount = null, AscScale $ascScale = null) : array
    {
        App::getContainer()->wireService('EventPackage/entity/CalendarEvent');
        $currentDate = new \DateTime();
        $additionalParameters = [
            ['refKey' => 'ce.start_date', 'paramKey' => 'ce_start_date', 'operator' => '<=', 'value' => $currentDate->format('Y-m-d')],
            ['refKey' => 'au.status', 'paramKey' => 'status', 'operator' => '=', 'value' => CalendarEvent::STATUS_ACTIVE]
        ];
        $data = self::findScaleCalendarEventData($userAccount, $ascScale, $additionalParameters);

        return $data;
    }

    public static function getExpiredRecurringCalendarEventData(UserAccount $userAccount = null, AscScale $ascScale = null) : array
    {
        return [];
    }

    public static function getClosedCalendarEventData(UserAccount $userAccount = null, AscScale $ascScale = null) : array
    {
        App::getContainer()->wireService('EventPackage/entity/CalendarEvent');
        $additionalParameters = [
            ['refKey' => 'au.status', 'paramKey' => 'status', 'value' => CalendarEvent::STATUS_CLOSED]
        ];
        $data = self::findScaleCalendarEventData($userAccount, $ascScale, $additionalParameters);
        
        return $data;
    }

    public static function getPostponedCalendarEventData(UserAccount $userAccount = null, AscScale $ascScale = null) : array
    {
        App::getContainer()->wireService('StatisticsPackage/service/CustomWeekManager');
        $currentWeekDates = CustomWeekManager::getWeekDates(CustomWeekManager::getWeekNumber(date('Y-m-d')), date('Y'));
        
        // $queryParams = self::getQueryParamsBase();
        // $queryParams[] = ['refKey' => 'ced.start_date', 'paramKey' => 'ced_start_date', 'operator' => '<=', 'value' => $currentWeekDates['weekStartDate']];
        // $queryParams[] = ['refKey' => 'au.status', 'paramKey' => 'status', 'value' => CalendarEvent::STATUS_ACTIVE];

        $additionalParameters = [
            ['refKey' => 'ced.start_date', 'paramKey' => 'ced_start_date', 'operator' => '<=', 'value' => $currentWeekDates['weekStartDate']],
            ['refKey' => 'au.status', 'paramKey' => 'status', 'value' => CalendarEvent::STATUS_ACTIVE]
        ];
        $data = self::findScaleCalendarEventData($userAccount, $ascScale, $additionalParameters);

        return $data;
    }

    public static function findScaleCalendarEventData(UserAccount $userAccount = null, AscScale $ascScale = null, $additionalParameters = [], $debug = false)
    {
        // $debug = true;
        App::getContainer()->wireService('projects/ASC/entity/AscScale');
        App::getContainer()->wireService('EventPackage/entity/CalendarEvent');

        if (!$userAccount) {
            $userAccount = App::getContainer()->getUser()->getUserAccount();
            if (!$userAccount) {
                return [];
            }
        }

        $stm = "SELECT 
            au.id as 'asc_unit_id',
            ce.id as 'calendar_event_id',
            ce.calendar_event_delay_id,
            ce.title as 'calendar_event_title', -- Right now it's unused
            ce.frequency_type,
            ce.start_date as 'ce_start_date',
            ce.start_time as 'ce_start_time',
            ced.start_date as 'ced_start_date',
            ced.start_time as 'ced_start_time',
            CASE WHEN ced.start_date IS NOT NULL 
                THEN ced.start_date 
                ELSE ce.start_date 
                END as 'due_date',
            ce.end_date, -- Right now it's unused
            ce.end_time, -- Right now it's unused
            ce.recurrence_unit,
            ce.recurrence_interval,
            ce.recurrence_day_mon,
            ce.recurrence_day_tue,
            ce.recurrence_day_wed,
            ce.recurrence_day_thu,
            ce.recurrence_day_fri,
            ce.recurrence_day_sat,
            ce.recurrence_day_sun
        FROM asc_unit au 
        JOIN asc_scale as units_scale ON units_scale.id = au.asc_scale_id 
        JOIN user_account ua ON ua.id = units_scale.user_account_id 
        JOIN calendar_event ce ON au.calendar_event_id = ce.id
        LEFT JOIN calendar_event_delay ced ON ce.calendar_event_delay_id = ced.id
        WHERE ce.event_type = :event_type 
        ".($ascScale ? "AND au.asc_scale_id = :asc_scale_id " : "")."
        ";

        $dbm = App::getContainer()->getDbManager();
        $params = [
            'event_type' => AscUnit::DUE_CALENDAR_EVENT_TYPE,
        ];

        if ($ascScale) {
            $params['asc_scale_id'] = $ascScale->getId();
        }

        $whereConditions = [];
        foreach ($additionalParameters as $param) {
            $refKey = $param['refKey'];
            $paramKey = $param['paramKey'];
            $value = $param['value'];
            $operator = isset($param['operator']) ? $param['operator'] : '=';
            $whereConditions[] = "$refKey $operator :$paramKey";
            $params[$paramKey] = $value;
        }

        if (!empty($whereConditions)) {
            $stm .= "AND " . implode(" AND ", $whereConditions);
        }

        $result = $dbm->findAll($stm, $params);

        if ($debug) {
            dump(nl2br($stm));
            dump($params);
            dump($result);
            // exit;
        }

        return $result;
    }

    public function appendAscUnitObject($calendarEventData)
    {
        $result = [];
        foreach ($calendarEventData as $calendarEventDataRow) {
            $ascUnitObject = $this->find($calendarEventDataRow['asc_unit_id']);
            $calendarEventDataRow['ascUnitObject'] = $ascUnitObject;
            $result[] = $calendarEventDataRow;
        }

        return $result;
    }
}