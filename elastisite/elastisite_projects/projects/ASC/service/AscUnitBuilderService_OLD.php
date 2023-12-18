<?php
namespace projects\ASC\service;

use App;
use framework\component\parent\Service;
use projects\ASC\entity\AscEntryHead;
use projects\ASC\entity\AscScale;
use projects\ASC\entity\AscUnit;
use projects\ASC\repository\AscUnitFileRepository;
use projects\ASC\repository\AscUnitRepository;

class AscUnitBuilderService extends Service
{

    /**
     * Nem csak elkeri a repo-tol a skalat, hanem meg is nezi, hogy jogosult vagy-e.
    */
    public static function getScale($scaleId)
    {
        if (!$scaleId) {
            return null;
        }
        App::getContainer()->setService('projects/ASC/repository/AscScaleRepository');
        App::getContainer()->setService('projects/ASC/service/AscRequestService');

        $repo = App::getContainer()->getService('AscScaleRepository');

        $ascScale = $repo->find($scaleId);

        if ($ascScale && AscRequestService::isAllowedScale($ascScale)) {
            return $ascScale;
        }

        return null;
    }

    /**
     * Ezt a tombot hasznaljuk minden skala megjeleniteshez.
    */
    public static function getUnitBuilderData(AscScale $ascScale, string $currentSubject = null, string $juxtaposedSubject = null)
    {
        App::getContainer()->wireService('projects/ASC/service/AscTechService');
        $subjectConfig = AscTechService::SUBJECT_CONFIG;
        $totalNumberOfUnitPanels = 0;
        $totalNumberOfSubjectPanels = 0;
        $subjectPanels = [];

        // $numberOf
        $numberOfUnitPanelsUsedByCurrentSubject = 0;
        if ($currentSubject) {
            if (!AscTechService::issetPrimarySubject($currentSubject)) {
                return false;
            }

            $totalNumberOfSubjectPanels++;
            $numberOfUnitPanelsUsedByCurrentSubject = $subjectConfig[$currentSubject]['numberOfUnitPanels'];
            $totalNumberOfUnitPanels += $numberOfUnitPanelsUsedByCurrentSubject;

            $subjectPanels['currentSubject'] = [
                'subjectName' => $currentSubject,
                'subjectSpot' => 'currentSubject',
                'numberOfUnitPanels' => $numberOfUnitPanelsUsedByCurrentSubject
            ];

            $subjectPanels['currentSubject']['unitPanels']['subject'][$currentSubject] = self::collectUnitPanelData($ascScale, $currentSubject);
            $parentProperties = self::getParentProperties($currentSubject);
            $subjectPanels['currentSubject']['unitPanels']['parentProperties'] = $parentProperties;
            // dump($ascScale);
            $subjectPanels['currentSubject']['unitPanels']['parentData'] = $parentProperties ? self::getParentData($ascScale->getId(), $parentProperties['singularRefName']) : null;
            $subjectPanels['currentSubject']['translationReferenceSingular'] = $subjectPanels['currentSubject']['unitPanels']['subject'][$currentSubject]['translationReferenceSingular'];
            $subjectPanels['currentSubject']['translationReferencePlural'] = $subjectPanels['currentSubject']['unitPanels']['subject'][$currentSubject]['translationReferencePlural'];

            if ($currentSubject == AscTechService::SUBJECT_IDEAL_SCENE) {
                $subjectPanels['currentSubject']['unitPanels']['subject'][AscTechService::SUBJECT_REAL_SCENE] = self::collectUnitPanelData($ascScale, AscTechService::SUBJECT_REAL_SCENE);
            }
        }

        $numberOfUnitPanelsUsedByJuxtaposedSubject = 0;
        if ($juxtaposedSubject) {
            if (!AscTechService::issetPrimarySubject($juxtaposedSubject)) {
                return false;
            }
            $totalNumberOfSubjectPanels++;
            $numberOfUnitPanelsUsedByJuxtaposedSubject = $subjectConfig[$juxtaposedSubject]['numberOfUnitPanels'];
            $totalNumberOfUnitPanels += $numberOfUnitPanelsUsedByJuxtaposedSubject;
            $subjectPanels['juxtaposedSubject'] = [
                'subjectName' => $juxtaposedSubject,
                'subjectSpot' => 'juxtaposedSubject',
                'numberOfUnitPanels' => $numberOfUnitPanelsUsedByJuxtaposedSubject
            ];

            $subjectPanels['juxtaposedSubject']['unitPanels']['subject'][$juxtaposedSubject] = self::collectUnitPanelData($ascScale, $juxtaposedSubject);
            $parentProperties = self::getParentProperties($juxtaposedSubject);
            $subjectPanels['juxtaposedSubject']['unitPanels']['parentProperties'] = $parentProperties;
            $subjectPanels['juxtaposedSubject']['unitPanels']['parentData'] = $parentProperties ? self::getParentData($ascScale->getId(), $parentProperties['singularRefName']) : null;
            $subjectPanels['juxtaposedSubject']['translationReferenceSingular'] = $subjectPanels['juxtaposedSubject']['unitPanels']['subject'][$juxtaposedSubject]['translationReferenceSingular'];
            $subjectPanels['juxtaposedSubject']['translationReferencePlural'] = $subjectPanels['juxtaposedSubject']['unitPanels']['subject'][$juxtaposedSubject]['translationReferencePlural'];

            if ($juxtaposedSubject == AscTechService::SUBJECT_IDEAL_SCENE) {
                $subjectPanels['juxtaposedSubject']['unitPanels']['subject'][AscTechService::SUBJECT_REAL_SCENE] = self::collectUnitPanelData($ascScale, AscTechService::SUBJECT_REAL_SCENE);
            }
        }

        // $planningData = [];
        // if (in_array(AscTechService::SUBJECT_PLAN , [$currentSubject, $juxtaposedSubject])) {
        //     $planningData = self::collectPlanningEntryData($ascScale);
        //     // dump($planningData);exit;
        // }

        // dump($subject);
        // dump($juxtaposedSubject);

        $rawResult = [
            'totalNumberOfUnitPanels' => $totalNumberOfUnitPanels,
            'totalNumberOfSubjectPanels' => $totalNumberOfSubjectPanels,
            'subjectPanels' => $subjectPanels,
            // 'parentData' => $parentData
            // 'planningEntryData' => $planningData
        ];

        // dump($rawResult);exit;

        $return = self::rearrangeSubjectPanels($rawResult);

        // dump($return);exit;
        // dump($rawResult);exit;

        return $return;
    }

    private static function getParentProperties($subject)
    {
        App::getContainer()->wireService('projects/ASC/service/AscTechService');
        $parentSubject = AscTechService::getParentSubjectProperties($subject);

        return $parentSubject;
    }

    private static function getParentData($ascScaleId, $subject)
    {
        $parentData = [];
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $ascUnitRepository = new AscUnitRepository();
        $parentRawData = $ascUnitRepository->getUnitData($ascScaleId, $subject);

        // dump($parentRawData);exit;
        foreach ($parentRawData as $parentRawDataRow) {
            $ascUnit = $ascUnitRepository->find($parentRawDataRow['asc_unit_id']);

            // dump($ascUnit->getAscEntryHead()->findEntry());exit;

            $unitData = self::getUnitData($ascUnit);
            // dump($unitData);exit;
            $parentData[] = $unitData['data'];
            $ascUnit = $unitData['object'];
        }
        // dump($parentData);exit;

        return $parentData;
    }

    /**
     * Erre a megjelenites miatt van szukseg. Es csak azert, mert az IdealScenes 2 panelbol all. Muszaj szetszedni az oszlopokat sorokra, kulonben a kulonbozo meretu Ideal- vagy Real scene-k nem lesznek egy sorban.
    */
    private static function rearrangeSubjectPanels($result) : array
    {
        if (isset($result['subjectPanels']['currentSubject'])) {
            $result['subjectPanels']['currentSubject'] = self::rearrangeSubjectPanel($result['subjectPanels']['currentSubject']);
        }

        if (isset($result['subjectPanels']['juxtaposedSubject'])) {
            $result['subjectPanels']['juxtaposedSubject'] = self::rearrangeSubjectPanel($result['subjectPanels']['juxtaposedSubject']);
        }

        return $result;
    }

    /**
     * A fenti fv hivja, ez 1 SubjectPanel-t keszit el.
    */
    private static function rearrangeSubjectPanel($rawData) : array
    {
        $mainData = [];
        $subjectRowPanelData = [];

        /**
         * Note: Csak az IdealScenes eseten van 2 UnitPanel.
        */
        $highestUnitsCount = 0;
        foreach ($rawData['unitPanels']['subject'] as $subjectName => $unitPanelData) {
            if (count($unitPanelData['unitsData']) > $highestUnitsCount) {
                $highestUnitsCount = count($unitPanelData['unitsData']);
            }
        }

        // foreach ($rawData['unitPanels']['parent'] as $subjectName => $unitPanelData) {
        //     if (count($unitPanelData['unitsData']) > $highestUnitsCount) {
        //         $highestUnitsCount = count($unitPanelData['unitsData']);
        //     }
        // }

        foreach ($rawData['unitPanels']['subject'] as $subjectName => $unitPanelData) {
            $mainData[$subjectName] = [
                'unitPanelName' => $unitPanelData['unitPanelName'],
                'translationReferenceSingular' => $unitPanelData['translationReferenceSingular'],
                'translationReferencePlural' => $unitPanelData['translationReferencePlural'],
            ];

            for ($i = 0; $i < ($highestUnitsCount); $i++) {
                if (isset($unitPanelData['unitsData'][$i])) {
                    $subjectRowPanelData[$i][$subjectName] = $unitPanelData['unitsData'][$i];
                } else {
                    $subjectRowPanelData[$i][$subjectName] = null;
                }
            }
        }

        $return = [
            'mainProperties' => $mainData,
            'parentProperties' => $rawData['unitPanels']['parentProperties'],
            'parentData' => $rawData['unitPanels']['parentData'],
            'subjectRowPanelData' => $subjectRowPanelData
        ];

        // dump($subjectRowPanelData);

        return $return;
    }

    private static function collectUnitPanelData(AscScale $ascScale, $unitPanelName) : array
    {
        $translationReferenceSingular = AscTechService::findSubjectConfigValue($unitPanelName, 'translationReferenceSingular');
        $translationReferencePlural = AscTechService::findSubjectConfigValue($unitPanelName, 'translationReferencePlural');

        $unitPanelData = [
            'unitPanelName' => $unitPanelName,
            'translationReferenceSingular' => $translationReferenceSingular,
            'translationReferencePlural' => $translationReferencePlural,
            'unitsData' => self::collectPrimarySubjectUnitPanelData($ascScale->getId(), $unitPanelName),
        ];

        return $unitPanelData;
    }

    /**
     * This is one of the the main methods of the entire software
    */
    public static function collectPrimarySubjectUnitPanelData(int $ascScaleId, string $primarySubject, string $position = null) : array
    {
        $unitPanelData = [];
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $ascUnitRepository = new AscUnitRepository();
        $unitPanelRawData = $ascUnitRepository->getUnitData($ascScaleId, $primarySubject, $position);

        foreach ($unitPanelRawData as $unitPanelRawDataRow) {
            $ascUnit = $ascUnitRepository->find($unitPanelRawDataRow['asc_unit_id']);

            // dump($ascUnit->getAscEntryHead()->findEntry());exit;

            $unitData = self::getUnitData($ascUnit);
            // dump($unitData);exit;
            $unitPanelData[] = $unitData['data'];
            $ascUnit = $unitData['object'];
        }

        return $unitPanelData;
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

    public static function getUnitData(AscUnit $ascUnit) : array
    {
        // dump($ascUnit->getAscEntryHead());
        if (!$ascUnit->getAscEntryHead()) {
            // App::getContainer()->wireService('projects/ASC/repository/AscEntryHeadRepository');
            App::getContainer()->wireService('projects/ASC/entity/AscEntryHead');
            $ascUnit->setAscEntryHead(self::createUnitEntryHead($ascUnit));
        }

        App::getContainer()->wireService('projects/ASC/repository/AscUnitFileRepository');
        $fileRepo = new AscUnitFileRepository();
        $files = $fileRepo->findBy(['conditions' => [
            ['key' => 'asc_unit_id', 'value' => $ascUnit->getId()]
        ]]);

        $mainEntry = $ascUnit->getAscEntryHead()->findEntry();
        $unitData = [
            'object' => $ascUnit,
            'data' => [
                'ascUnitId' => $ascUnit->getId(),
                'parentId' => $ascUnit->getParent() ? $ascUnit->getParent()->getId() : null,
                'subject' => $ascUnit->getSubject(),
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
                'dueType' => $ascUnit->getDueType(),
                'recurrencePattern' => $ascUnit->getRecurrencePattern(),
                'dueDate' => $ascUnit->getDueDate(),
                'dueTime' => $ascUnit->getDueTime(),
                'responsible' => $ascUnit->getResponsible() ? $ascUnit->getResponsible()->getPerson()->getFullName() : '',
                'status' => $ascUnit->getStatus(),
                'files' => $files
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
