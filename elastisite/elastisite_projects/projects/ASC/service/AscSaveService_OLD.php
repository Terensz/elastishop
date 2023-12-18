<?php
namespace projects\ASC\service;

use App;
use framework\component\parent\Service;
use projects\ASC\entity\AscEntry;
use projects\ASC\entity\AscEntryHead;
use projects\ASC\entity\AscScale;
use projects\ASC\entity\AscUnit;
use projects\ASC\repository\AscScaleRepository;
use projects\ASC\repository\AscUnitRepository;

class AscSaveService extends Service
{
    const PLACEHOLDER_TARGET_PARENT_TYPE_PRIMARY_SUBJECT = 'primarySubject';
    const PLACEHOLDER_TARGET_PARENT_TYPE_UNIT = 'unit';

    public static function saveScaleHeader(string $situation, string $languageCode, string $title, string $description = null)
    {
        // App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        App::getContainer()->wireService('projects/ASC/service/AscTechService');
        App::getContainer()->wireService('projects/ASC/entity/AscScale');
        App::getContainer()->wireService('projects/ASC/entity/AscEntryHead');
        App::getContainer()->wireService('projects/ASC/entity/AscEntry');
        App::getContainer()->wireService('projects/ASC/service/AscTranslatorService');

        // dump(App::getContainer()->getUser());exit;
        
        $creatorUserAccount = App::getContainer()->getUser()->getUserAccount();

        // $titleAscTranslation = AscTranslatorService::createTranslation($languageCode, $title);
        // $descriptionAscTranslation = AscTranslatorService::createTranslation($languageCode, $description);

        $ascEntryHead = new AscEntryHead();
        $ascEntryHead->setSubjectCategory(AscTechService::SCALE);
        $ascEntryHead = $ascEntryHead->getRepository()->store($ascEntryHead);

        $ascEntry = new AscEntry();
        $ascEntry->setAscEntryHead($ascEntryHead);
        $ascEntry->setLanguageCode($languageCode);
        $ascEntry->setTitle($title);
        $ascEntry->setDescription($description);
        $ascEntry->setStatus(AscEntry::STATUS_ACTIVE);
        $ascEntry = $ascEntry->getRepository()->store($ascEntry);

        $ascScale = new AscScale();
        $ascScale->setSituation($situation);
        $ascScale->setAscEntryHead($ascEntryHead);
        $ascScale->setUserAccount($creatorUserAccount);
        $ascScale->setStatus(AscScale::STATUS_UNDER_CONSTRUCTION);
        $ascScale = $ascScale->getRepository()->store($ascScale);

        return $ascScale;
    }

    public static function savePrimarySubjectUnit()
    {
        $saveResult = [
            'errorMessage' => null,
            'entity' => null
        ];
        App::getContainer()->wireService('projects/ASC/entity/AscScale');
        App::getContainer()->wireService('projects/ASC/service/AscRequestService');
        $processedRequestData = AscRequestService::getProcessedRequestData();

        $ascUnit = null;
        if ($processedRequestData['errorMessage']) {
            $saveResult['errorMessage'] = $processedRequestData['errorMessage'];
        } else {
            $ascUnit = self::saveUnit($processedRequestData['ascScale'], $processedRequestData['subject'], $processedRequestData['parentAscUnit'], );
        }

        // dump($ascUnit);
        // dump($processedRequestData);exit;
        return $ascUnit;
    }

    public static function arrangeUnitSequence(int $scaleId, string $subject, $parentId = null)
    {
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $ascUnitRepository = new AscUnitRepository();
        $ascUnitRepository->arrangeSequence($scaleId, $subject, $parentId);
    }

    public static function moveUnitTo()
    {
        App::getContainer()->wireService('projects/ASC/service/AscRequestService');

        $processedRequestData = AscRequestService::getProcessedRequestData();

        // dump($processedRequestData);exit;

        // $processedRequestData['ascUnit'] = null;
        // $processedRequestData['parentAscUnit'] = null;
        // $processedRequestData['targetAscUnit'] = null;
        // dump($processedRequestData);exit;

        if ($processedRequestData['errorMessage']) {
            return [
                'success' => false,
                'errorMessage' => $processedRequestData['errorMessage']
            ];
        }

        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $ascUnitRepository = new AscUnitRepository();

        $toPlaceholder = false;

        if (!in_array($processedRequestData['aheadOrBehind'], [AscUnitRepository::MOVE_TO_POSITION_AHEAD, AscUnitRepository::MOVE_TO_POSITION_BEHIND])) {
            return false;
        }

        if ($processedRequestData['unitId'] == $processedRequestData['targetUnitId']) {
            return false;
        }

        $movedAscUnit = $processedRequestData['ascUnit'];
        if (!$movedAscUnit) {
            return false;
        }

        /**
         * Eloszor is: a biztonsag kedveert elrendezzuk a sorszamokat a regi es az uj subject alatt.
        */
        if ($movedAscUnit && $movedAscUnit->getSubject()) {
            $ascUnitRepository->arrangeSequence($processedRequestData['ascScale']->getId(), $movedAscUnit->getSubject());
        }
        if ($processedRequestData['subject']) {
            $ascUnitRepository->arrangeSequence($processedRequestData['ascScale']->getId(), $processedRequestData['subject']);
        }

        $ascScale = $movedAscUnit->getAscScale();

        $targetParentType = null;
        if ($processedRequestData['targetUnitId'] == AscRequestService::TARGET_UNIT_ID_PLACEHOLDER) {
            $toPlaceholder = true;
            $targetParentType = $processedRequestData['parentType'];

            if ($targetParentType == self::PLACEHOLDER_TARGET_PARENT_TYPE_PRIMARY_SUBJECT) {
                $newSubject = $processedRequestData['subject'];
                if (!AscTechService::issetSubject($newSubject)) {
                    /**
                     * @todo bunti
                    */
                    dump('Non-existing subject: ');
                    dump($newSubject);
                    dump(App::getContainer()->getRequest()->getAll());
                    dump($movedAscUnit);exit;
                    return false;
                }
                $movedAscUnit->setParent(null);
                $movedAscUnit->setSubject($newSubject);
                $movedAscUnit->setSequenceNumber(null);
                $movedAscUnit = $movedAscUnit->getRepository()->store($movedAscUnit);
                // $ascUnitRepository->arrangeSequence($ascScale->getId(), $newSubject);

                // dump($movedAscUnit);exit;

                return $movedAscUnit;
            } 
            // elseif ($placeholderToType == self::PLACEHOLDER_TO_TYPE_UNIT) {
            // }
        }

        if (!$toPlaceholder) {
            $targetAscUnit = $ascUnitRepository->find($processedRequestData['targetUnitId']);
            $targetScale = $targetAscUnit->getAscScale();
            /**
             * Nehogy csaljanak a toAscUnitId-val
            */
            if ($ascScale->getId() != $targetScale->getId()) {
                /**
                 * @todo bunti
                */
                dump('target scale is different!!!!');
                dump($targetScale->getId());
                dump(App::getContainer()->getRequest()->getAll());
                dump($movedAscUnit);exit;
                return false;
            }
        }

        // $toParentAscUnit = null;
        // if ($toParentAscUnitId) {
        //     $toParentAscUnit = $ascUnitRepository->find($toParentAscUnitId);
        // }
        /**
         * Nehogy csaljanak a toParentAscUnitId-val
        */
        // if ($toParentAscUnit && !AscRequestService::isAllowedScale($toParentAscUnit->getAscScale())) {
        //     /**
        //      * @todo bunti
        //     */
        //     return false;
        // }

        /**
         * Placeholderhez mozgattuk, meghozza unithoz, vagyis Plan-hez vagy Program-hoz vagy Project-hez. 
         * A tobbi subject-hez mozgatas eseten a placeholderToType az "subject" lesz, vagyis idaig nem jutunk el ebben a metodusban.
        */
        if ($toPlaceholder && $targetParentType == self::PLACEHOLDER_TARGET_PARENT_TYPE_UNIT) {
            // $targetParentAscUnit = $ascUnitRepository->find($processedRequestData['parentId']);
            $movedAscUnit->setParent($processedRequestData['parentAscUnit']);
            $movedAscUnit->setSubject($processedRequestData['subject']);
            $movedAscUnit->setSequenceNumber(null);
            $movedAscUnit = $movedAscUnit->getRepository()->store($movedAscUnit);
            // $ascUnitRepository->arrangeSequence($ascScale->getId(), $processedRequestData['subject']);

            return $movedAscUnit;
        }

        /**
         * Kitesszuk valtozoba a regit.
        */
        $oldSubject = $movedAscUnit->getSubject();
        /**
         * Egyelore null-t allitunk be, hogy tudjuk rendezni a regi subjectet nelkule.
        */
        $movedAscUnit->setSubject(null);

        // $processedRequestData['subject']
        /**
         * Beallitjuk az uj parentjet.
        */
        $movedAscUnit->setParent($processedRequestData['parentAscUnit']);
        $movedAscUnit = $ascUnitRepository->store($movedAscUnit);
        if ($movedAscUnit === false) {
            // return false;
            dump('Hibás');
            dump(App::getContainer()->getRequest()->getAll());
            $processedRequestData['ascUnit'] = null;
            $processedRequestData['parentAscUnit'] = null;
            $processedRequestData['targetAscUnit'] = null;
            dump($processedRequestData);exit;
        }
        /**
         * Miutan null-ra allitottuk a subjectjet az elmozgatott unitnak, a regi subjectje alatt rendezzuk a lyukat.
        */
        $ascUnitRepository->arrangeSequence($ascScale->getId(), $oldSubject);
        /**
         * Es beekeljuk az uj helyere.
        */
        // $ascUnitRepository->arrangeSequence($ascScale->getId(), $processedRequestData['targetAscUnit']->getSubject());
        $movedAscUnit = $ascUnitRepository->wedgeUnitTo(
            $ascScale->getId(), 
            $processedRequestData['targetAscUnit']->getSubject(), 
            $movedAscUnit, 
            $processedRequestData['targetAscUnit']->getId(), 
            $processedRequestData['aheadOrBehind']
        );

        if (!$movedAscUnit->getSubject()) {
            $movedAscUnit->setSubject($oldSubject);
            $ascUnitRepository->store($movedAscUnit);
        }

        return $movedAscUnit;
    }

    // public static function moveUnitTo(int $movedAscUnitId, $toAscUnitId, $toParentAscUnitId = null, string $aheadOrBehind = AscUnitRepository::MOVE_TO_POSITION_AHEAD)
    // {
    //     App::getContainer()->wireService('projects/ASC/service/AscTechService');
    //     App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
    //     $toPlaceholder = false;

    //     if (!in_array($aheadOrBehind, [AscUnitRepository::MOVE_TO_POSITION_AHEAD, AscUnitRepository::MOVE_TO_POSITION_BEHIND])) {
    //         return false;
    //     }

    //     if ($movedAscUnitId == $toAscUnitId) {
    //         return false;
    //     }

    //     // App::getContainer()->wireService('projects/ASC/repository/AscScaleRepository');
    //     // $ascScaleRepo = new AscScaleRepository();
    //     App::getContainer()->wireService('projects/ASC/service/AscRequestService');

    //     $ascUnitRepository = new AscUnitRepository();
    //     $movedAscUnit = $ascUnitRepository->find($movedAscUnitId);
    //     if (!$movedAscUnit) {
    //         return false;
    //     }

    //     $ascScale = $movedAscUnit->getAscScale();
    //     /**
    //      * Nehogy csaljanak a movedAscUnitId-val
    //     */
    //     if (!AscRequestService::isAllowedScale($ascScale)) {
    //         /**
    //          * @todo bunti
    //         */
    //         return false;
    //     }

    //     // (0)[movedUnitId] => 215041
    //     // (1)[toUnitId] => placeholder-subject-
    //     // (2)[toParentId] => IdealScene
    //     // (3)[aheadOrBehind] => ahead
    //     // dump(App::getContainer()->getRequest()->getAll());
    //     // dump($movedAscUnit);exit;

    //     $placeholderPos = strpos($toAscUnitId, 'placeholder-');
    //     if ($placeholderPos !== false) {
    //         $toPlaceholder = true;
    //         $placeholderToUnitParts = explode('-', $toAscUnitId);
    //         $placeholderToType = $placeholderToUnitParts[1];
    //         $unitMutatesTo = isset($placeholderToUnitParts[2]) && $placeholderToUnitParts[2] ? $placeholderToUnitParts[2] : null;
    //         // dump('Miva1');
    //         // dump($unitMutatesTo);
    //         // dump(App::getContainer()->getRequest()->getAll());
    //         // dump($movedAscUnit);exit;
    //         if (!AscTechService::issetMutatableToSubject($unitMutatesTo)) {
    //             /**
    //              * @todo bunti
    //             */
    //             dump('Miva1');
    //             dump($unitMutatesTo);
    //             dump(App::getContainer()->getRequest()->getAll());
    //             dump($movedAscUnit);exit;
    //             return false;
    //         }
    //         // dump('Mivot1');
    //         // dump($unitMutatesTo);
    //         // dump(App::getContainer()->getRequest()->getAll());
    //         // dump($movedAscUnit);exit;

    //         if ($placeholderToType == self::PLACEHOLDER_TO_TYPE_SUBJECT) {
    //             $newSubject = $toParentAscUnitId;
    //             if (!AscTechService::issetSubject($newSubject)) {
    //                 /**
    //                  * @todo bunti
    //                 */
    //                 dump('Miva2');
    //                 dump($newSubject);
    //                 dump(App::getContainer()->getRequest()->getAll());
    //                 dump($movedAscUnit);exit;
    //                 return false;
    //             }
    //             $movedAscUnit->setParent(null);
    //             $movedAscUnit->setSubject($newSubject);
    //             $movedAscUnit->setSequenceNumber(null);
    //             $movedAscUnit = $movedAscUnit->getRepository()->store($movedAscUnit);
    //             $ascUnitRepository->arrangeSequence($ascScale->getId(), $newSubject);

    //             // dump($movedAscUnit);exit;

    //             return $movedAscUnit;
    //         } 
    //         // elseif ($placeholderToType == self::PLACEHOLDER_TO_TYPE_UNIT) {
    //         // }
    //     }

    //     if (!$toPlaceholder) {
    //         $toAscUnit = $ascUnitRepository->find($toAscUnitId);
    //         $toScale = $toAscUnit->getAscScale();
    //         /**
    //          * Nehogy csaljanak a toAscUnitId-val
    //         */
    //         if ($ascScale->getId() != $toScale->getId()) {
    //             /**
    //              * @todo bunti
    //             */
    //             return false;
    //         }
    //     }

    //     $toParentAscUnit = null;
    //     if ($toParentAscUnitId) {
    //         $toParentAscUnit = $ascUnitRepository->find($toParentAscUnitId);
    //     }
    //     /**
    //      * Nehogy csaljanak a toParentAscUnitId-val
    //     */
    //     if ($toParentAscUnit && !AscRequestService::isAllowedScale($toParentAscUnit->getAscScale())) {
    //         /**
    //          * @todo bunti
    //         */
    //         return false;
    //     }

    //     /**
    //      * Placeholderhez mozgattuk, meghozza unithoz, vagyis Plan-hez vagy Program-hoz vagy Project-hez. 
    //      * A tobbi subject-hez mozgatas eseten a placeholderToType az "subject" lesz, vagyis idaig nem jutunk el ebben a metodusban.
    //     */
    //     if ($toPlaceholder) {
    //         $movedAscUnit->setParent($toParentAscUnit);
    //         $movedAscUnit->setSubject($unitMutatesTo);
    //         $movedAscUnit->setSequenceNumber(null);
    //         $movedAscUnit = $movedAscUnit->getRepository()->store($movedAscUnit);
    //         $ascUnitRepository->arrangeSequence($ascScale->getId(), $unitMutatesTo);

    //         return $movedAscUnit;
    //     }

    //     /**
    //      * Mivel a mozgatott subjectunk mar nem tartozik a regi subject ala, igy kivesszuk onnan.
    //      * De elotte azert kitesszuk valtozoba a regit.
    //     */
    //     $oldSubject = $movedAscUnit->getSubject();
    //     $movedAscUnit->setSubject(null);
    //     /**
    //      * Beallitjuk az uj parentjet.
    //     */
    //     $movedAscUnit->setParent($toParentAscUnit);
    //     $movedAscUnit = $ascUnitRepository->store($movedAscUnit);
    //     /**
    //      * Miutan null-ra allitottuk a subjectjet az elmozgatott unitnak, a regi subjectje alatt rendezzuk a lyukat.
    //     */
    //     $ascUnitRepository->arrangeSequence($ascScale->getId(), $oldSubject);
    //     /**
    //      * Es beekeljuk az uj helyere.
    //     */
    //     $ascUnitRepository->arrangeSequence($ascScale->getId(), $toAscUnit->getSubject());
    //     $movedAscUnit = $ascUnitRepository->wedgeUnitTo($ascScale->getId(), $toAscUnit->getSubject(), $movedAscUnit, $toAscUnit->getId(), $aheadOrBehind);

    //     return $movedAscUnit;
    // }

    public static function saveUnit(AscScale $ascScale, string $subject, AscUnit $parent = null, $position = AscUnit::POSITION_LEFT)
    {
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        App::getContainer()->wireService('projects/ASC/service/AscTechService');
        App::getContainer()->wireService('projects/ASC/entity/AscUnit');
        App::getContainer()->wireService('projects/ASC/entity/AscEntry');

        $ascUnitRepository = new AscUnitRepository();
        $ascUnitRepository->arrangeSequence($ascScale->getId(), $subject, ($parent && is_object($parent) ? $parent->getId() : null));
        $nextSequence = $ascUnitRepository->getNextSequence($ascScale->getId(), $subject, ($parent && is_object($parent) ? $parent->getId() : null));

        $ascUnit = new AscUnit();
        $ascUnit->setAscScale($ascScale);
        $ascUnit->setSubject($subject);
        $ascUnit->setParent($parent);
        $ascUnit->setPosition($position);
        $ascUnit->setSequenceNumber($nextSequence);
        if (App::getContainer()->getUser() && App::getContainer()->getUser()->getUserAccount()->getId()) {
            $ascUnit->setCreatedBy(App::getContainer()->getUser()->getUserAccount());
        }

        $ascUnitRepository->arrangeSequence($ascScale->getId(), $subject, ($parent ? $parent->getId() : null));

        // echo '<pre>';
        // var_dump($ascUnit);
        // exit;

        $ascUnit = $ascUnitRepository->store($ascUnit);

        return $ascUnit;
    }
}