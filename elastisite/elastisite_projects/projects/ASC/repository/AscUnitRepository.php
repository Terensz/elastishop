<?php

namespace projects\ASC\repository;

use App;
use framework\component\parent\DbRepository;
use framework\packages\EventPackage\entity\CalendarEvent;
use framework\packages\EventPackage\entity\CalendarEventActuality;
use framework\packages\EventPackage\repository\CalendarCheckEventActualityRepository;
use framework\packages\StatisticsPackage\service\CustomWeekManager;
use framework\packages\UserPackage\entity\UserAccount;
use projects\ASC\entity\AscScale;
use projects\ASC\entity\AscUnit;
use projects\ASC\service\AscPermissionService;
use projects\ASC\service\AscCalendarEventService;
use projects\ASC\service\AscUnitBuilderService;

class AscUnitRepository extends DbRepository
{
    const MOVE_TO_POSITION_AHEAD = 'ahead';
    const MOVE_TO_POSITION_BEHIND = 'behind';

    const ALL_PARENTS = '-all-';

    /**
     * THe main purpose of this method is to speed up the queries for making lists.
    */
    public static function getRawUnitsData(array $filter = [], array $ids = [], $debug = false)
    {
        // $debug = true;
        $whereStringAdd = '';
        $params = [] ;
        $params['language_code'] = App::getContainer()->getSession()->getLocale();

        if (!empty($filter)) {
            foreach ($filter as $key => $value) {
                $paramKey = str_replace('.', '_', $key);
                $params[$paramKey] = $value;
                $whereStringAdd .= " AND $key = :$paramKey ";
            }
        }

        if (!empty($ids)) {
            $idPlaceholders = implode(',', array_map(function($index) {
                return ":id$index";
            }, array_keys($ids))); // Sorszámozott helykitöltők
            $whereStringAdd .= " AND au.id IN ($idPlaceholders) ";
            foreach ($ids as $index => $id) {
                $params[":id$index"] = $id; // Hozzáadja az ID-ket a $params tömbhöz sorszámozott kulcsokkal
            }
        }
        

        $dbm = App::getContainer()->getDbManager();
        $stm = "SELECT 
                    au.id as asc_unit_id ,
                    au.created_at as au_created_at ,
                    -- pref_e.title as title_pref , -- preferred 
                    GROUP_CONCAT(DISTINCT eh.id) as entry_head_id ,
                    au.status as asc_unit_status ,
                    responsible_ua.id as responsible_user_account_id ,
                    au.calendar_event_id as asc_unit_calendar_event_id , 
                    GROUP_CONCAT(DISTINCT
                        CASE
                            WHEN (pref_e.title IS NULL OR pref_e.title = '') THEN def_e.id
                            ELSE pref_e.id
                        END
                        SEPARATOR ', '
                    ) AS main_entry_id,
                    GROUP_CONCAT(DISTINCT
                        CASE 
                            WHEN (pref_e.title IS NULL OR pref_e.title = '') THEN def_e.title
                            ELSE pref_e.title
                        END
                        SEPARATOR ', '
                    ) AS main_entry_title,
                    GROUP_CONCAT(DISTINCT
                        CASE 
                            WHEN (pref_e.description IS NULL OR pref_e.description = '') THEN def_e.description
                            ELSE pref_e.description
                        END
                        SEPARATOR ', '
                    ) AS main_entry_description, 
                    GROUP_CONCAT(DISTINCT
                        CASE
                            WHEN (pref_e.title IS NULL OR pref_e.title = '') THEN def_e.language_code
                            ELSE pref_e.language_code
                        END
                        SEPARATOR ', '
                    ) AS main_entry_language_code,
                    au.parent_id as parent_id ,
                    au.subject as subject , 
                    COUNT(child.id) as child_count 
                FROM asc_unit au 
                INNER JOIN asc_entry_head eh ON eh.asc_unit_id = au.id 
                LEFT JOIN asc_entry pref_e ON pref_e.asc_entry_head_id = eh.id AND pref_e.language_code = :language_code
                LEFT JOIN asc_entry def_e ON def_e.asc_entry_head_id = eh.id AND def_e.default_language = 1 
                LEFT JOIN asc_unit child ON child.parent_id = au.id 
                LEFT JOIN user_account responsible_ua ON responsible_ua.id = au.responsible 
                WHERE 1 = 1 
                ".$whereStringAdd."
                GROUP BY au.id 
                ORDER BY au.sequence_number ASC 
                ";

        if ($debug) {
            dump(nl2br($stm));
            dump($params);
        }
        
        $result = $dbm->findAll($stm, $params);

        // dump($result);

        return $result;
    }

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
        if (!AscPermissionService::checkScalePermission(AscPermissionService::PERMISSION_DELETE_SCALE, $ascUnit->getAscScale())) {
            return false;
        }

        // dump($ascUnit);exit;
        
        $dbm = $this->getDbManager();
        $stm = "DELETE FROM asc_unit WHERE id = :id ";

        $result = $dbm->execute($stm, ['id' => $id]);

        return $result;
    }

    public function getAscUnitIdsForOptions(int $ascScaleId, string $subject = null)
    {
        if (!$ascScaleId) {
            return [];
        }

        $dbm = $this->getDbManager();
        $stm = "SELECT 
                au.id as asc_unit_id
        FROM asc_unit au 
        WHERE au.asc_scale_id = :asc_scale_id
        ".($subject ? "AND au.subject = :subject " : "")."
        ";

        $params = [
            'asc_scale_id' => $ascScaleId
        ];

        if ($subject) {
            $params['subject'] = $subject;
        }

        // dump($stm);
        // dump($params);
        // exit;

        $rawResult = $dbm->findAll($stm, $params);
        $result = [];
        foreach ($rawResult as $rawResultRow) {
            $result[] = $rawResultRow['asc_unit_id'];
        }

        // dump($result);exit;

        // dump($projectTeamId);
        // dump($ascScaleId);
        // dump($ascUnitId);
        // dump($subject);
        // exit;
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

    public function collectDashboardData(UserAccount $userAccount = null)
    {
        if (!$userAccount) {
            return [];
        }
        // dump($userAccount);exit;
        App::getContainer()->wireService('EventPackage/entity/CalendarEventActuality');

        $dbm = $this->getDbManager();
        $stm = "SELECT 
            cal_ev.id as 'calendar_event_id',
            cal_ev.start_date as 'calendar_event_start_date',
            cal_ev.start_time as 'calendar_event_start_time',
            cal_ev_act.id as 'calendar_event_actuality_id',
            cal_ev_act.status as 'calendar_event_actuality_status',
            cal_chk_ev_act.id as 'calendar_check_event_actuality_id',
            cal_chk.run_date as 'calendar_check_run_date',
            unit.id as 'asc_unit_id',
            unit.asc_scale_id as 'asc_scale_id',
            unit.modified_at as 'asc_unit_modified_at',
            ua.id as 'user_account_id'
        FROM calendar_event_actuality cal_ev_act 
        LEFT JOIN calendar_event cal_ev ON cal_ev.id = cal_ev_act.calendar_event_id 
        INNER JOIN calendar_check_event_actuality cal_chk_ev_act ON cal_chk_ev_act.calendar_event_actuality_id = cal_ev_act.id 
        INNER JOIN calendar_check cal_chk ON cal_chk.id = cal_chk_ev_act.calendar_check_id
        LEFT JOIN asc_unit unit ON unit.calendar_event_id = cal_ev.id 
        INNER JOIN asc_scale scl ON scl.id = unit.asc_scale_id 
        INNER JOIN user_account ua ON ua.id = cal_ev_act.user_account_id
        WHERE ua.id = :user_account_id 
        ORDER BY cal_ev.start_date ASC, cal_ev.start_time ASC
        -- AND cal_ev_act.status NOT IN (".CalendarEventActuality::STATUS_CLOSED_SUCCESSFUL.", ".CalendarEventActuality::STATUS_CLOSED_FAILED.")
        ";
        $queryResult = $dbm->findAll($stm, [
            'user_account_id' => $userAccount->getId()
        ]);

        // App::getContainer()->wireService('EventPackage/repository/CalendarCheckEventActualityRepository');
        // $calendarCheckEventActualityRepository = new CalendarCheckEventActualityRepository();

        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $ascUnitRepository = new AscUnitRepository();

        App::getContainer()->wireService('projects/ASC/service/AscUnitBuilderService');

        $return = [];
        foreach ($queryResult as $queryResultRow) {
            $ascUnit = $ascUnitRepository->find($queryResultRow['asc_unit_id']);
            $ascUnitData = AscUnitBuilderService::createUnitDataFromObject($ascUnit);
            $return[] = [
                'ascScale_id' => $queryResultRow['asc_scale_id'],
                'ascUnit_id' => $queryResultRow['asc_unit_id'],
                'ascUnit_modifiedAt' => $queryResultRow['asc_unit_modified_at'],
                'calendarEvent_startDate' => $queryResultRow['calendar_event_start_date'],
                'calendarEvent_startTime' => $queryResultRow['calendar_event_start_time'],
                'calendarEvent_id' => $queryResultRow['calendar_event_id'],
                'calendarEventActuality_id' => $queryResultRow['calendar_event_actuality_id'],
                'calendarEventActuality_status' => $queryResultRow['calendar_event_actuality_status'],
                'calendarCheck_runDate' => $queryResultRow['calendar_check_run_date'],
                'ascUnitData' => $ascUnitData
            ];
        }

        return $return;
    }
}