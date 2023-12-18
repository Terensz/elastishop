<?php
namespace projects\ASC\service;

use App;
use framework\component\parent\Service;
use framework\packages\EventPackage\service\CalendarEventFactory;
use projects\ASC\entity\AscEntryHead;
use projects\ASC\entity\AscScale;
use projects\ASC\entity\AscUnit;
use projects\ASC\repository\AscUnitFileRepository;
use projects\ASC\repository\AscUnitRepository;

class AscUnitBuilderService extends Service
{
    const NULL_KEY = '-null-';

    // public static function arrangeToListViewStructure_OLD(array $unitDataArray)
    // {
    //     App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
    //     $ascUnitRepo = new AscUnitRepository();

    //     $nullKeyElements = [];
    //     $otherElements = [];

    //     $result = [
    //         'list' => [],
    //         'parents' => []
    //     ];

    //     $parentKeys = [];
    //     foreach ($unitDataArray as $unitDataArrayRow) {
    //         $parentKey = $unitDataArrayRow['data']['parentId'] ? : self::NULL_KEY;
    //         /**
    //          * We want to know the index, so we have to create it in advance
    //         */
    //         if (!isset($result['list'][$parentKey])) {
    //             $result['list'][$parentKey] = [];
    //         }
    //         $listIndex = count($result['list'][$parentKey]);
    //         $result['list'][$parentKey][$listIndex] = $unitDataArrayRow;
    //         if ($unitDataArrayRow['data']['parentId'] && !in_array($unitDataArrayRow['data']['parentId'], $parentKeys)) {
    //             $parentObject = $ascUnitRepo->find($unitDataArrayRow['data']['parentId']);
    //             if ($parentObject) {
    //                 $parentKeys[] = $unitDataArrayRow['data']['parentId'];
    //                 $parentUnitData = self::assembleUnitData($parentObject);
    //                 $result['parents'][$unitDataArrayRow['data']['parentId']] = $parentUnitData;
    //             } else {
    //                 /**
    //                  * If we have accidently a database error, we handle this.
    //                 */
    //                 $result['list'][$parentKey][$listIndex]['data']['parentId'] = null;
    //             }
    //         }
    //     }
    //     // dump($result);exit;
    //     return $result;
    // }

    public static function arrangeToListViewStructure(array $unitDataArray)
    {
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $ascUnitRepo = new AscUnitRepository();

        $result = [
            'list' => [],
            'parents' => []
        ];
        $parentKeys = [];
        $nullKeyElements = [];
        $otherElements = [];

        foreach ($unitDataArray as $unitDataArrayRow) {
            $parentKey = $unitDataArrayRow['data']['parentId'] ? : self::NULL_KEY;

            /**
             * We want the result array to begin with key: self::NULL_KEY for those units which does not belong to a parent.
             * And to continue with keys which are actially the ID of the parent.
            */
            if ($parentKey === self::NULL_KEY) {
                if (!isset($nullKeyElements[$parentKey])) {
                    $nullKeyElements[$parentKey] = [];
                }
                $listIndex = count($nullKeyElements[$parentKey]);
                $nullKeyElements[$parentKey][$listIndex] = $unitDataArrayRow;
            } else {
                if (!isset($otherElements[$parentKey])) {
                    $otherElements[$parentKey] = [];
                }
                $listIndex = count($otherElements[$parentKey]);
                $otherElements[$parentKey][$listIndex] = $unitDataArrayRow;
            }

            if ($unitDataArrayRow['data']['parentId'] && !in_array($unitDataArrayRow['data']['parentId'], $parentKeys)) {
                $parentObject = $ascUnitRepo->find($unitDataArrayRow['data']['parentId']);
                if ($parentObject) {
                    $parentKeys[] = $unitDataArrayRow['data']['parentId'];
                    $parentUnitData = self::assembleUnitData($parentObject);
                    $result['parents'][$unitDataArrayRow['data']['parentId']] = $parentUnitData;
                } else {
                    /**
                     * If we have accidentally a database error, we handle this.
                    */
                    if ($parentKey === self::NULL_KEY) {
                        $nullKeyElements[$parentKey][$listIndex]['data']['parentId'] = null;
                    } else {
                        $otherElements[$parentKey][$listIndex]['data']['parentId'] = null;
                    }
                }
            }
        }

        if (isset($nullKeyElements[self::NULL_KEY])) {
            $result['list'][self::NULL_KEY] = $nullKeyElements[self::NULL_KEY];
        }
        foreach ($otherElements as $parentKey => $elements) {
            $result['list'][$parentKey] = $elements;
        }

        // dump(array_keys($result['list']));//exit;
        // dump($nullKeyElements); dump($otherElements);exit;

        return $result;
    }

    /**
     * for List view
    */
    public static function getUnitDataArrayOfSubject(AscScale $ascScale, string $subject)
    {
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $ascUnitRepo = new AscUnitRepository();
        $ascUnitObjects = $ascUnitRepo->findBy([
            'conditions' => [
                ['key' => 'asc_scale_id', 'value' => $ascScale->getId()],
                ['key' => 'subject', 'value' => $subject]
            ],
            'orderBy' => [['field' => 'sequence_number', 'direction' => 'ASC']]
        ]);
        $unitDataArray = self::createUnitDataArray($ascUnitObjects);
        // dump($unitDataArray);

        // return $unitDataArray;
        return self::arrangeToListViewStructure($unitDataArray);
    }

    /**
     * for Column view
    */
    public static function getColumnUnitDataArrayOfSubject(AscScale $ascScale, string $subject, $selected = null)
    {
        App::getContainer()->wireService('projects/ASC/entity/AscScale');
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $ascUnitRepo = new AscUnitRepository();
        // $ascUnitObjects = $ascUnitRepo->findBy(['conditions' => [['key' => 'subject', 'value' => $subject]]]);
        // $unitDataArray = self::createUnitDataArray($ascUnitObjects);
        // $res = $unitDataArray;
        $columnViewParentData = [];

        $ascUnitObjects = $ascUnitRepo->findBy([
            'conditions' => [
                ['key' => 'asc_scale_id', 'value' => $ascScale->getId()],
                ['key' => 'subject', 'value' => $subject]
            ],
            'orderBy' => [['field' => 'sequence_number', 'direction' => 'ASC']]
        ]);
        if (!empty($ascUnitObjects)) {
            $columnData = self::createUnitDataArray($ascUnitObjects, $selected);
            $columnViewParentData[] = [
                'parentUnitData' => null,
                'columnUnitsData' => $columnData,
                'headerData' => [
                    'link' => null,
                    // 'link' => '/asc/scaleBuilder/columnView/scale/'.$ascScale->getId().'/subject/'.$subject,
                    'title' => trans(AscTechService::findSubjectConfigValue($subject, 'translationReferencePlural'))
                ]
            ];
        }

        $return = [
            'columnViewParentData' => $columnViewParentData,
            'columnViewActualUnitData' => null
        ];

        // dump($return);exit;

        return $return;
    }

    /**
     * for List view
    */
    public static function getUnitDataArrayOfParent(AscUnit $parentAscUnit) : array
    {
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $ascUnitRepo = new AscUnitRepository();
        $ascUnitObjects = $ascUnitRepo->findBy([
            'conditions' => [['key' => 'parent_id', 'value' => $parentAscUnit->getId()]],
            'orderBy' => [['field' => 'sequence_number', 'direction' => 'ASC']]
        ]);
        $unitDataArray = self::createUnitDataArray($ascUnitObjects);

        if (empty($unitDataArray)) {
            return [
                'list' => [
                    $parentAscUnit->getId() => []
                ],
                'parents' => [
                    $parentAscUnit->getId() => self::assembleUnitData($parentAscUnit)
                ]
            ];
        }

        return self::arrangeToListViewStructure($unitDataArray);
    }

    /**
     * for Column view
    */
    public static function getColumnViewData(AscUnit $ascUnit)
    {
        $columnViewParentData = [];
        $ascUnitRepo = new AscUnitRepository();
        $originalAscUnit = clone $ascUnit;
        $originalAscUnitData = self::assembleUnitData($originalAscUnit);
        $ascScale = $ascUnit->getAscScale();

        $lastSubject = null;
        $lastAscUnit = null;
        while ($ascUnit) {
            $selectedId = $ascUnit->getId();
            $parent = $ascUnit->getParent();
            $lastAscUnit = clone $ascUnit;

            $lastSubject = $ascUnit->getSubject();

            if ($parent) {
                $ascUnitObjects = $ascUnitRepo->findBy([
                    'conditions' => [['key' => 'parent_id', 'value' => $parent->getId()]],
                    'orderBy' => [['field' => 'sequence_number', 'direction' => 'ASC']]
                ]);
                $columnData = self::createUnitDataArray($ascUnitObjects, $selectedId);
            } else {
                $columnData = null;
            }

            if (!empty($columnData)) {
                $columnViewParentData[] = [
                    'parentUnitData' => self::assembleUnitData($parent),
                    'columnUnitsData' => $columnData
                ];
            }

            $ascUnit = $parent;
        }

// dump(self::assembleUnitData($lastAscUnit));
        // dump($lastSubject);exit;

        if ($lastSubject) {
            $rootData = self::getColumnUnitDataArrayOfSubject($ascScale, $lastSubject, $lastAscUnit->getId())['columnViewParentData'][0];
            // dump($rootData, ['detailedObjects' => false]);exit;
            $columnViewParentData[] = $rootData;
        }

        $columnViewParentData = array_reverse($columnViewParentData);

        // dump($columnViewParentData, ['detailedObjects' => false]);exit;

        $childAscUnitObjects = $ascUnitRepo->findBy([
            'conditions' => [['key' => 'parent_id', 'value' => $originalAscUnit->getId()]],
            'orderBy' => [['field' => 'sequence_number', 'direction' => 'ASC']]
        ]);
        if (!empty($childAscUnitObjects)) {
            $columnData = self::createUnitDataArray($childAscUnitObjects, null);
            $columnViewParentData[] = [
                'parentUnitData' => $originalAscUnitData,
                'columnUnitsData' => $columnData
            ];
        }

        // return $columnViewParentData;
        return [
            'columnViewParentData' => $columnViewParentData,
            'columnViewActualUnitData' => $originalAscUnitData
        ];
    }

    // public static function searchParentsAllSubs($parentAscUnit, $markThis = true, $ascUnitId)
    // {
    //     $ascUnitRepo = new AscUnitRepository();
    //     $ascUnitObjects = $ascUnitRepo->findBy(['conditions' => [['key' => 'parent_id', 'value' => $parentAscUnit->getId()]]]);

    //     $result = [];

    //     foreach ($ascUnitObjects as $ascUnit) {
    //         $isSelected = ($ascUnit->getId() === $ascUnitId) && $markThis;
    //         $subUnits = self::searchParentsAllSubs($ascUnit, false, $ascUnitId);

    //         $result[] = self::assembleUnitData($ascUnit, $isSelected, $subUnits);
    //     }

    //     return $result;
    // }

    // public static function searchParentsAllSubs($parentAscUnit, $markThis = true, $ascUnitId)
    // {
    //     $ascUnitRepo = new AscUnitRepository();
    //     $ascUnitObjects = $ascUnitRepo->findBy(['conditions' => [['key' => 'parent_id', 'value' => $parentAscUnit->getId()]]]);

    //     $result = [];

    //     foreach ($ascUnitObjects as $ascUnit) {
    //         $isSelected = ($ascUnit->getId() === $ascUnitId) && $markThis;
    //         $subUnits = self::searchParentsAllSubs($ascUnit, false, $ascUnitId);

    //         $result[] = self::assembleUnitData($ascUnit, $isSelected, $subUnits);
    //     }

    //     return $result;
    // }

    // public static function searchParentsAllSubs($parentAscUnit, $markThis = true, $ascUnitId)
    // {
    //     $ascUnitRepo = new AscUnitRepository();
    //     $ascUnitObjects = $ascUnitRepo->findBy(['conditions' => [['key' => 'parent_id', 'value' => $parentAscUnit->getId()]]]);

    //     $result = [];

    //     foreach ($ascUnitObjects as $ascUnit) {
    //         $isSelected = ($ascUnit->getId() === $ascUnitId) && $markThis;
    //         $subUnits = self::searchParentsAllSubs($ascUnit, false, $ascUnitId);

    //         $result[] = self::assembleUnitData($ascUnit, $isSelected, $subUnits);
    //     }

    //     // Rekurzió a szülőkön
    //     if ($parentAscUnit->getParent()) {
    //         $parentSubUnits = self::searchParentsAllSubs($parentAscUnit->getParent(), false, $ascUnitId);
    //         // array_unshift($result, ...$parentSubUnits);
    //     }

    //     return $result;
    // }

    public static function createUnitDataArray(array $ascUnitObjects, int $selectedId = null) : array
    {
        App::getContainer()->wireService('projects/ASC/service/AscPermissionService');
        $accessibility = AscPermissionService::getCurrentScaleAccessibility();

        // dump($accessibility);exit;

        $result = [];
        foreach ($ascUnitObjects as $ascUnit) {
            if ($accessibility['permissions']['ScaleOwner'] || (is_array($accessibility['accessibleUnitIds']) && in_array($ascUnit->getId(), $accessibility['accessibleUnitIds']))) {
                $result[] = self::assembleUnitData($ascUnit, ($ascUnit->getId() == $selectedId ? true : false));
            }
        }

        return $result;
    }

    public static function createUnitEntryHead(AscUnit $ascUnit) : AscEntryHead
    {
        $ascEntryHead = new AscEntryHead();
        $found = $ascEntryHead->getRepository()->findOneBy(['conditions' => [['key' => 'asc_unit_id', 'value' => $ascUnit->getId()]]]);
        if ($found) {
            return $found;
        }
        $ascEntryHead->setAscUnit($ascUnit);
        $ascEntryHead = $ascEntryHead->getRepository()->store($ascEntryHead);

        return $ascEntryHead;
    }

    public static function assembleUnitData(AscUnit $ascUnit, bool $selected = false) : array
    {
        App::getContainer()->wireService('projects/ASC/service/AscTechService');
        // dump($ascUnit->getAscEntryHead());
        if (!$ascUnit->getAscEntryHead()) {
            // App::getContainer()->wireService('projects/ASC/repository/AscEntryHeadRepository');
            App::getContainer()->wireService('projects/ASC/entity/AscEntryHead');
            $ascUnit->setAscEntryHead(self::createUnitEntryHead($ascUnit));
        }

        App::getContainer()->wireService('projects/ASC/repository/AscUnitFileRepository');
        $fileRepo = new AscUnitFileRepository();
        $fileObjects = $fileRepo->findBy(['conditions' => [
            ['key' => 'asc_unit_id', 'value' => $ascUnit->getId()]
        ]]);
        $thumbnailSources = [];
        foreach ($fileObjects as $fileObject) {
            $thumbnailSources[] = '/asc/unitImage/thumbnail/'.$fileObject->getCode();
        }

        $mainEntry = $ascUnit->getAscEntryHead()->findEntry();
        App::getContainer()->wireService('EventPackage/service/CalendarEventFactory');
        $dueHandler = $ascUnit->getDueEventFactory();
        $subjectData = $ascUnit->getSubject() ? AscTechService::getSubjectData($ascUnit->getSubject()) : null;
        $translatedSubjectSingular = $subjectData ? trans($subjectData['translationReferenceSingular']) : null;
        $unitData = [
            'object' => $ascUnit,
            // 'object' => null,
            'data' => [
                'ascUnitId' => $ascUnit->getId(),
                'parentId' => $ascUnit->getParent() ? $ascUnit->getParent()->getId() : null,
                'subject' => $ascUnit->getSubject(),
                'translatedSubjectSingular' => $translatedSubjectSingular,
                'subjectData' => $subjectData,
                'selected' => $selected,
                'subjectIsParentOf' => AscTechService::getChildSubjectData($ascUnit->getSubject()),
                'subjectIsChildOf' => AscTechService::getParentSubjectData($ascUnit->getSubject()),
                'ascUnitIsDeletable' => $ascUnit->getRepository()->isDeletable($ascUnit->getId()),
                // 'cratedBy' => $ascUnit->getCreatedBy()->getId(),
                // 'cratedByName' => $ascUnit->getCreatedBy()->getPerson()->getFullName(),
                // 'cratedByEmail' => $ascUnit->getCreatedBy()->getPerson()->getEmail(),
                'createdAt' => $ascUnit->getCreatedAt(),
                // 'entryHead' => $ascUnit->getAscEntryHead(),
                'mainEntryTitle' => $mainEntry ? $mainEntry->getTitle() : null,
                'mainEntryDescription' => $mainEntry ? $mainEntry->getDescription() : '',
                'mainEntryLanguage' => $mainEntry ? $mainEntry->getLanguageCode() : null,
                'ascEntryHeadId' => $ascUnit->getAscEntryHead()->getId(),
                'isDeletable' => $ascUnit->getRepository()->isDeletable($ascUnit->getId()),
                'frequencyType' => $dueHandler->getFrequencyType(),
                // 'recurrencePattern' => $ascUnit->getRecurrencePattern(),
                'dueDate' => $dueHandler->getStartDate(),
                'dueTime' => $dueHandler->getStartTime(),
                'responsible' => $ascUnit->getResponsible() ? $ascUnit->getResponsible()->getPerson()->getFullName() : '',
                'status' => $ascUnit->getStatus(),
                'thumbnailSources' => $thumbnailSources
                // 'mainEntry' => ,
                // 'entries' => $ascUnit->getAscEntryHead()->getAscEntry(),
            ]
        ];

        return $unitData;
    }

    // public static function collectPlanningEntryData(AscScale $ascScale) : array
    // {
    //     App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
    //     $ascUnitRepository = new AscUnitRepository();

    //     $programs = $ascUnitRepository->findBy([
    //         'conditions' => [['key' => 'subject', 'value' => AscTechService::SUBJECT_PROGRAM]],
    //         'orderBy' => [['field' => 'sequence_number', 'direction' => 'ASC']]
    //     ]);
    //     $programData = [];
    //     foreach ($programs as $ascUnit) {
    //         $unitData = self::getUnitData($ascUnit);
    //         $ascUnit = $unitData['object'];
    //         // $programData[$ascUnit->getParent()->getId()][$ascUnit->getId()] = $unitData['data'];
    //         if ($ascUnit->getParent()) {
    //             $programData[$ascUnit->getParent()->getId()][$ascUnit->getId()] = $unitData['data'];
    //         }
    //     }

    //     $projects = $ascUnitRepository->findBy([
    //         'conditions' => [['key' => 'subject', 'value' => AscTechService::SUBJECT_PROJECT]],
    //         'orderBy' => [['field' => 'sequence_number', 'direction' => 'ASC']]
    //     ]);
    //     $projectData = [];
    //     foreach ($projects as $ascUnit) {
    //         $unitData = self::getUnitData($ascUnit);
    //         $ascUnit = $unitData['object'];
    //         if ($ascUnit->getParent()) {
    //             $projectData[$ascUnit->getParent()->getId()][$ascUnit->getId()] = $unitData['data'];
    //         }
    //     }

    //     $targets = $ascUnitRepository->findBy([
    //         'conditions' => [['key' => 'subject', 'value' => AscTechService::SUBJECT_TARGET]],
    //         'orderBy' => [['field' => 'sequence_number', 'direction' => 'ASC']]
    //     ]);
    //     $targetsOfProgramsData = [];
    //     $targetsOfProjectsData = [];
    //     foreach ($targets as $ascUnit) {
    //         $unitData = self::getUnitData($ascUnit);
    //         $ascUnit = $unitData['object'];
    //         if ($ascUnit->getParent()) {
    //             $index = $ascUnit->getParent()->getId();
    //             if ($ascUnit->getParent()->getSubject() == AscTechService::SUBJECT_PROGRAM) {
    //                 $targetsOfProgramsData[$index][$ascUnit->getId()] = $unitData['data'];
    //             }
    //             if ($ascUnit->getParent()->getSubject() == AscTechService::SUBJECT_PROJECT) {
    //                 $targetsOfProjectsData[$index][$ascUnit->getId()] = $unitData['data'];
    //             }
    //         }
    //     }

    //     // dump($programData);exit;

    //     return [
    //         'programs' => $programData,
    //         'projects' => $projectData,
    //         'targetsOfPrograms' => $targetsOfProgramsData,
    //         'targetsOfProjects' => $targetsOfProjectsData,
    //     ];
    // }

    // public static function getUnitTitleData(AscUnit $ascUnit) : array
    // {

    // }
}
