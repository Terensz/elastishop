<?php
namespace projects\ASC\service;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use projects\ASC\entity\AscScale;
use projects\ASC\entity\AscUnit;
use projects\ASC\entity\ProjectTeamInvite;
use projects\ASC\repository\AscScaleRepository;
use projects\ASC\repository\AscTranslationRepository;
use projects\ASC\repository\AscUnitRepository;
use projects\ASC\repository\ProjectTeamInviteRepository;

class AscRequestService extends Service
{
    const VIEW_TYPE_LIST_VIEW = 'ListView';
    const VIEW_TYPE_COLUMN_VIEW = 'ColumnView';

    const UNIT_TRANS_REF_UNIT = 'unit';
    const UNIT_TRANS_REF_PARENT = 'parent';
    const UNIT_TRANS_REF_TARGET_UNIT = 'target.unit';

    const TARGET_UNIT_ID_PLACEHOLDER = 'placeholder';

    public static $cache;

    public static function processURLRequest()
    {
        if (isset(self::$cache['URLRequests'])) {
            return self::$cache['URLRequests'];
        }

        $url = App::getContainer()->getUrl();

        $dedicatedStandaloneUrlElements = [
            'scaleBuilder', 'projectTeamwork', 'columnView'
        ];

        $dedicatedKeyUrlElements = [
            'scale', 'subject', 'juxtaposedSubject', 'parent', 'child'
        ];
    
        $convertGetParamKeyToResultKey = [
            'scale' => 'scaleId',
            'parent' => 'parentId',
            'child' => 'unitId'
        ];
    
        $urlElementArray = $url->getDetails();
        // dump();
        if (!is_array($urlElementArray)) {
            $urlElementArray = [];
        }
    
        $result = [];

        $subRoute = $url->getSubRoute();

        if ($subRoute == 'scaleBuilder') {
            $result['scaleBuilder'] = true;
        }

        if ($subRoute == 'projectTeamwork') {
            $result['projectTeamwork'] = true;
        }
    
        $currentKey = null;
        foreach ($urlElementArray as $urlElement) {
            // dump($urlElement);
            if (in_array($urlElement, $dedicatedKeyUrlElements)) {
                $currentKey = $urlElement;
                if (array_key_exists($currentKey, $convertGetParamKeyToResultKey)) {
                    $result[$convertGetParamKeyToResultKey[$currentKey]] = null;
                } else {
                    $result[$currentKey] = null;
                }
            } elseif ($currentKey !== null) {
                if (array_key_exists($currentKey, $convertGetParamKeyToResultKey)) {
                    $result[$convertGetParamKeyToResultKey[$currentKey]] = $urlElement;
                } else {
                    $result[$currentKey] = $urlElement;
                }
                $currentKey = null;
            /**
             * Standalone elements: which are not followed by a value.
             * Standalones are working like a boolean. It it occurs in the URL, than the thing it represents is true. 
            */
            } elseif (in_array($urlElement, $dedicatedStandaloneUrlElements)) {
                // dump($urlElement);
                $result[$urlElement] = true;
            }
        }

        // foreach ($dedicatedStandaloneUrlElements as $dedicatedStandaloneUrlElement) {
        //     if (!isset($result[$dedicatedStandaloneUrlElement])) {
        //         $result[$dedicatedStandaloneUrlElement] = false;
        //     }
        // }

        // dump($result);exit;

        self::$cache['URLRequests'] = $result;
    
        return $result;
    }

    /**
     * Kiegeszites 2023-08-13: mar az URL-requestet is feldolgozom.
    */
    public static function getProcessedRequestData()
    {
        if (isset(self::$cache['processedRequestData'])) {
            return self::$cache['processedRequestData'];
        }
        $scaleFromUrl = self::getScaleFromUrl();
        $scaleId = StringHelper::mendValue(App::getContainer()->getRequest()->get('scale'));
        $subject = StringHelper::mendValue(App::getContainer()->getRequest()->get('subject'));
        $unitId = StringHelper::mendValue(App::getContainer()->getRequest()->get('unitId'));
        $targetUnitId = StringHelper::mendValue(App::getContainer()->getRequest()->get('targetUnitId'));
        $parentType = StringHelper::mendValue(App::getContainer()->getRequest()->get('parentType'));
        $parentId = StringHelper::mendValue(App::getContainer()->getRequest()->get('parentId'));
        // $childId = StringHelper::mendValue(App::getContainer()->getRequest()->get('childId'));
        $position = StringHelper::mendValue(App::getContainer()->getRequest()->get('position'));
        // $dueDate = StringHelper::mendValue(App::getContainer()->getRequest()->get('dueDate'));
        // $dueTimeHours = StringHelper::mendValue(App::getContainer()->getRequest()->get('dueTimeHours'));
        // $dueTimeMinutes = StringHelper::mendValue(App::getContainer()->getRequest()->get('dueTimeMinutes'));
        $submitted = StringHelper::mendValue(App::getContainer()->getRequest()->get('submitted'));
        $aheadOrBehind = App::getContainer()->getRequest()->get('aheadOrBehind');

        if ($parentId == '') {
            $parentId = null;
        }

        /**
         * Ugy is mukodni fog, ha nem postolod el a scaleId-t, csak az URL-ben van benne.
         * Ugy viszont nem, ha a postolt scaleId nem ugyanaz, mint a get-bol jovo.
        */
        if (!$scaleId && $scaleFromUrl) {
            $scaleId = $scaleFromUrl->getId();
        }

        $rawRequestData = [
            'scaleBuilder' => false,
            'projectTeamwork' => false,
            'scaleId' => $scaleId,
            'columnView' => false,
            'subject' => $subject,
            'juxtaposedSubject' => null,
            'unitId' => $unitId,
            'targetUnitId' => $targetUnitId,
            'parentType' => $parentType,
            'parentId' => $parentId,
            'position' => $position,
            // 'dueDate' => $dueDate,
            // 'dueTimeHours' => $dueTimeHours,
            // 'dueTimeMinutes' => $dueTimeMinutes,
            'submitted' => $submitted,
            'aheadOrBehind' => $aheadOrBehind,
            'ascScale' => null,
            'ascUnit' => null,
            'parentAscUnit' => null,
            'targetAscUnit' => null,
            'errorMessage' => null
        ];

        $processedURLRequest = self::processURLRequest();

        // dump($processedURLRequest);

        foreach ($processedURLRequest as $key => $value) {
            if (array_key_exists($key, $rawRequestData)) {
                $rawRequestData[$key] = $value;
            }
        }

        // dump($processedURLRequest);
        if ($rawRequestData['unitId'] && !$rawRequestData['scaleId']) {
            App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
            $ascUnitRepository = new AscUnitRepository();
            $ascUnit = $ascUnitRepository->find($rawRequestData['unitId']);
            if (!$ascUnit) {
                throw new \Exception('Invalid unit!');
            }
            $rawRequestData['scaleId'] = $ascUnit->getAscScale()->getId();
        }
        // dump($rawRequestData);exit;

        if ($scaleFromUrl && $scaleId && $scaleFromUrl->getId() != $scaleId) {
            $requestData = self::nullRequestData($rawRequestData);
            $requestData['errorMessage'] = 'scale.is.invalid';
            return $requestData;
        }

        $requestData = self::secureCheckAndRequests($rawRequestData);
        self::$cache['processedRequestData'] = $requestData;

        return $requestData;
    }

    public static function secureCheckAndRequests($requestData)
    {
        // $requestData['unitId'] = 215002;
        if ($requestData['scaleId']) {
            App::getContainer()->wireService('projects/ASC/repository/AscScaleRepository');
            $ascScaleRepo = new AscScaleRepository();
            $ascScale = $ascScaleRepo->find($requestData['scaleId']);

            if ($ascScale) {
                App::getContainer()->wireService('projects/ASC/service/AscPermissionService');
                $scaleIsAllowed = AscPermissionService::checkScalePermission($ascScale, AscPermissionService::PERMISSION_TYPE_VIEW) == true ? $requestData['scaleId'] : null;
                if ($scaleIsAllowed == false) {
                    $requestData = self::nullRequestData($requestData);
                    $requestData['errorMessage'] = 'scale.not.allowed';
                    return $requestData;
                } else {
                    $requestData['ascScale'] = $ascScale;

                    if ($requestData['subject']) {
                        App::getContainer()->wireService('projects/ASC/service/AscTechService');
                        $subjectData = AscTechService::findSubjectConfigValue($requestData['subject'], 'type');
                        if (!$subjectData) {
                            $requestData = self::nullRequestData($requestData);
                            $requestData['errorMessage'] = 'subject.is.invalid';
                            return $requestData;
                        }
                    }

                    if ($requestData['juxtaposedSubject']) {
                        App::getContainer()->wireService('projects/ASC/service/AscTechService');
                        $subjectData = AscTechService::findSubjectConfigValue($requestData['juxtaposedSubject'], 'type');
                        if (!$subjectData) {
                            $requestData = self::nullRequestData($requestData);
                            $requestData['errorMessage'] = 'juxtaposed.subject.is.invalid';
                            return $requestData;
                        }
                    }


                    if ($requestData['unitId']) {
                        $requestData = self::findAndCheckUnit($requestData['unitId'], $requestData, 'ascUnit', self::UNIT_TRANS_REF_UNIT);
                    }

                    if ($requestData['parentId']) {
                        $requestData = self::findAndCheckUnit($requestData['parentId'], $requestData, 'parentAscUnit', self::UNIT_TRANS_REF_PARENT);
                    }

                    // if ($requestData['childId']) {
                    //     $requestData = self::findAndCheckUnit($requestData['parentId'], $requestData, 'parentAscUnit', self::UNIT_TRANS_REF_PARENT);
                    // }

                    if ($requestData['targetUnitId']) {
                        $requestData = self::findAndCheckUnit($requestData['targetUnitId'], $requestData, 'targetAscUnit', self::UNIT_TRANS_REF_TARGET_UNIT, [self::TARGET_UNIT_ID_PLACEHOLDER]);
                    }
                }
            } else {
                $requestData = self::nullRequestData($requestData);
                $requestData['errorMessage'] = 'scale.not.found';
                return $requestData;
            }
        }

        if (!$requestData['scaleId']) {
            $requestData['subject'] = null;
            $requestData['position'] = null;
        }

        return $requestData;
    }

    public static function nullRequestData($requestData)
    {
        $requestData['scaleId'] = null;
        $requestData['subject'] = null;
        $requestData['unitId'] = null;
        $requestData['targetUnitId'] = null;
        $requestData['parentType'] = null;
        $requestData['parentId'] = null;
        $requestData['position'] = null;
        // $requestData['dueDate'] = null;
        // $requestData['dueTimeHours'] = null;
        // $requestData['dueTimeMinutes'] = null;
        $requestData['submitted'] = null;
        $requestData['ascScale'] = null;
        $requestData['ascUnit'] = null;
        $requestData['parentAscUnit'] = null;
        $requestData['targetAscUnit'] = null;
        $requestData['aheadOrBehind'] = null;

        return $requestData;
    }

    // public static function findAndCheckScale(int $ascScaleId = null)
    // {
    //     $scale = null;
    //     $viewPermission = false;
    //     $editPermission = false;
    //     if ($ascScaleId) {
    //         App::getContainer()->wireService('projects/ASC/repository/AscScaleRepository');
    //         $scaleRepo = new AscScaleRepository();
    //         $scale = $scaleRepo->find($ascScaleId);
    //         if ($scale) {
    //             if (AscRequestService::isAllowedScale($scale, true)) {
    //                 $viewPermission = true;
    //                 $editPermission = true;
    //             }
    //             if (AscRequestService::isAllowedScale($scale, true)) {
    //                 $viewPermission = true;
    //                 $editPermission = true;
    //             }
    //         }
    //     }

    //     return [
    //         'ascScale' => $scale,
    //         'viewPermission' => $viewPermission,
    //         'editPermission' => $editPermission
    //     ];
    // }

    // public static function findAndCheckUnit_OLD($unitId, array $requestData, string $objectIndex, string $unitTransRef = self::UNIT_TRANS_REF_UNIT, array $acceptedNonDatabaseUnitIdValues = [])
    // {
    //     if (in_array($unitId, $acceptedNonDatabaseUnitIdValues)) {
    //         return $requestData;
    //     }

    //     App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
    //     $ascUnitRepo = new AscUnitRepository();

    //     $ascUnit = $ascUnitRepo->find($unitId);
    //     if (!$ascUnit) {
    //         $requestData = self::nullRequestData($requestData);
    //         $requestData['errorMessage'] = $unitTransRef.'.is.invalid';
    //     } else {
    //         $ascUnitIsAllowed = self::isAllowedUnit($ascUnit);
    //         if (!$ascUnitIsAllowed) {
    //             $requestData = self::nullRequestData($requestData);
    //             $requestData['errorMessage'] = $unitTransRef.'.is.unauthorized';
    //             return $requestData;
    //         } else {
    //             $requestData[$objectIndex] = $ascUnit;
    //         }
    //     }

    //     return $requestData;
    // }

    public static function findAndCheckUnit($unitId, array $requestData, string $objectIndex, string $unitTransRef = self::UNIT_TRANS_REF_UNIT, array $acceptedNonDatabaseUnitIdValues = []) : array
    {
        if (in_array($unitId, $acceptedNonDatabaseUnitIdValues)) {
            return $requestData;
        }

        $unitAndProperties = self::findUnitAndGetProperties($unitId, $unitTransRef);
        /**
         * @var $unitAndProperties format:
        */
        /*
        return [
            'ascUnitObject' => $forceAllButErrorMessageToNull ? null : ($ascUnit ? : null),
            'ascUnitId' => $forceAllButErrorMessageToNull ? null : ($ascUnit ? $ascUnit->getId() : null),
            'errorMessage' => ($errorMessage ? : null),
        ];
        */

        if ($unitAndProperties['errorMessage']) {
            $requestData = self::nullRequestData($requestData);
            $requestData['errorMessage'] = $unitAndProperties['errorMessage'];
        } else {
            $requestData[$objectIndex] = $unitAndProperties['ascUnitObject'];
        }

        // dump($requestData);

        return $requestData;
    }

    /**
     * General method of the project 
     * =============================
     * This is a general method callable by any part of the software.
     * Finds the requested unit, and also checks if it's available for the user.
    */
    public static function findUnitAndGetProperties($unitId, string $unitTransRef = self::UNIT_TRANS_REF_UNIT) : array
    {
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $ascUnitRepo = new AscUnitRepository();

        $errorMessage = null;
        $ascUnit = $ascUnitRepo->find($unitId);

        if (!$ascUnit) {
            $errorMessage = $unitTransRef.'.is.invalid';
        } else {
            App::getContainer()->wireService('projects/ASC/service/AscPermissionService');

            $unitViewPermission = AscPermissionService::checkUnitPermission($ascUnit, AscPermissionService::PERMISSION_TYPE_VIEW);
            if (!$unitViewPermission) {
                $errorMessage = $unitTransRef.'.is.unauthorized';
            }
        }
        $unitPropertiesArray = self::createUnitPropertiesArray(($errorMessage ? true : false), $ascUnit, $errorMessage);

        return $unitPropertiesArray;
    }

    /**
     * This method is used by self::findUnitAndGetProperties() method only!
    */
    private static function createUnitPropertiesArray($forceAllButErrorMessageToNull = false, AscUnit $ascUnit = null, $errorMessage = null)
    {
        /**
         * Ha nem erkezik ervenyes @var AscUnit $ascUnit, akkor az errormessage kivetelevel mindent force-olunk null-ra. 
         * Ezt a metodust amugy mas esetben is hivjuk force-olt ertekekkel, de ez itt egy automatizmus.
        */
        if (!$ascUnit) {
            $forceAllButErrorMessageToNull = true;
        }

        return [
            'ascUnitObject' => $forceAllButErrorMessageToNull ? null : ($ascUnit ? : null),
            'ascUnitId' => $forceAllButErrorMessageToNull ? null : ($ascUnit ? $ascUnit->getId() : null),
            'errorMessage' => ($errorMessage ? : null),
        ];
    }

    /**
     * TEMP!!! @todo Ez el fog tunni, amint megcsinaltam az isPermittedScale-t.
    */
    // public static function isAllowedScale(AscScale $ascScale, $ownerOnly = false)
    // {
    //     App::getContainer()->wireService('projects/ASC/entity/AscScale');

    //     return ($ascScale->getUserAccount() && $ascScale->getUserAccount()->getId() == App::getContainer()->getUser()->getUserAccount()->getId()) ? true : false;
    // }

    /**
     * TEMP!!! @todo Ez is el fog tunni, amint megcsinaltam az isPermittedScale-t.
    */
    // public static function isAllowedUnit(AscUnit $ascUnit)
    // {
    //     App::getContainer()->wireService('projects/ASC/entity/AscUnit');
    //     $ascScale = $ascUnit->getAscScale();
    //     if (!$ascScale) {
    //         return false;
    //     }

    //     return self::isAllowedScale($ascScale);
    // }

    public static function getScaleFromUrl()
    {
        $processedUrlRequest = self::processURLRequest();
        if (isset($processedUrlRequest['scaleId'])) {
            App::getContainer()->wireService('projects/ASC/service/AscPermissionService');
            $scaleId = $processedUrlRequest['scaleId'];
            $scaleParams = AscPermissionService::findAndCheckScale($scaleId);
            if ($scaleParams['ascScale'] && $scaleParams['viewPermission']) {
                return $scaleParams['ascScale'];
            }
        }

        return null;
    }

    public static function getSubjectFromUrl()
    {
        $processedUrlRequest = self::processURLRequest();
        if (isset($processedUrlRequest['subject'])) {
            $processedRequestData = self::getProcessedRequestData();
            return $processedRequestData['subject'];
        }

        return null;
    }

    public static function getJuxtaposedSubjectFromUrl()
    {
        $processedUrlRequest = self::processURLRequest();
        if (isset($processedUrlRequest['juxtaposedSubject'])) {
            $processedRequestData = self::getProcessedRequestData();
            return $processedRequestData['juxtaposedSubject'];
        }

        return null;
    }

    public static function getParentObjectFromUrl()
    {
        $processedUrlRequest = self::processURLRequest();
        // dump($processedUrlRequest);
        if (isset($processedUrlRequest['parentId'])) {
            $processedRequestData = self::getProcessedRequestData();
            // dump($processedRequestData);exit;
            return $processedRequestData['parentAscUnit'];
        }

        return null;
    }

    // public static function getScaleFromUrl_OLD()
    // {
    //     if (isset(self::$cache['scale'])) {
    //         return self::$cache['scale'];
    //     }
    //     $url = App::getContainer()->getUrl()->getParamChain();
    //     $urlParts = explode('/', $url);
    //     $scaleFound = false;
    //     $scaleId = null;
    //     $scale = null;
    //     foreach ($urlParts as $urlPart) {
    //         if ($scaleFound) {
    //             $scaleId = $urlPart;
    //             $scaleFound = false;
    //             break;
    //         }
    //         if ($urlPart == 'scale') {
    //             $scaleFound = true;
    //         }
    //     }

    //     App::getContainer()->wireService('projects/ASC/service/AscUnitBuilderService');
    //     // $ascUnitBuilderService = new AscUnitBuilderService();

    //     if ($scaleId && is_numeric($scaleId)) {
    //         $scale = AscUnitBuilderService::getScale((int)$scaleId);
    //         if ($scale) {
    //             self::$cache['scale'] = $scale;
    //         }
    //     }
    //     // dump($scaleId);exit;
        
    //     return $scale;
    // }

    // public static function getSubjectFromUrl_OLD()
    // {
    //     if (isset(self::$cache['subject'])) {
    //         return self::$cache['subject'];
    //     }
    //     $url = App::getContainer()->getUrl()->getParamChain();
    //     $urlParts = explode('/', $url);
    //     $subjectFound = false;
    //     $subject = null;
    //     foreach ($urlParts as $urlPart) {
    //         if ($subjectFound) {
    //             $subject = $urlPart;
    //             $subjectFound = false;
    //             break;
    //         }
    //         if ($urlPart == 'subject') {
    //             $subjectFound = true;
    //         }
    //     }

    //     if ($subject) {
    //         self::$cache['subject'] = $subject;
    //     }
        
    //     return $subject;
    // }

    // public static function getJuxtaposedSubjectFromUrl_OLD()
    // {
    //     if (isset(self::$cache['juxtaposedSubject'])) {
    //         return self::$cache['juxtaposedSubject'];
    //     }
    //     $url = App::getContainer()->getUrl()->getParamChain();
    //     $urlParts = explode('/', $url);
    //     $subjectFound = false;
    //     $subject = null;
    //     foreach ($urlParts as $urlPart) {
    //         if ($subjectFound) {
    //             $subject = $urlPart;
    //             $subjectFound = false;
    //             break;
    //         }
    //         if ($urlPart == 'juxtaposedSubject') {
    //             $subjectFound = true;
    //         }
    //     }

    //     if ($subject) {
    //         self::$cache['juxtaposedSubject'] = $subject;
    //     }
        
    //     return $subject;
    // }
}