<?php
namespace projects\ASC\service;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use projects\ASC\entity\AscScale;
use projects\ASC\entity\AscUnit;
use projects\ASC\repository\AscScaleRepository;
use projects\ASC\repository\AscTranslationRepository;
use projects\ASC\repository\AscUnitRepository;

class AscRequestService extends Service
{
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

        $dedicatedUrlElements = [
            'scale', 'subject', 'juxtaposedSubject', 'parent'
        ];
    
        $convertGetParamKeyToResultKey = [
            'scale' => 'scaleId'
        ];
    
        $urlElementArray = App::getContainer()->getUrl()->getDetails();
        if (!is_array($urlElementArray)) {
            $urlElementArray = [];
        }
    
        $result = [];
    
        $currentKey = null;
        foreach ($urlElementArray as $urlElement) {
            if (in_array($urlElement, $dedicatedUrlElements)) {
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
            }
        }

        self::$cache['URLRequests'] = $result;
    
        return $result;
    }

    /**
     * Kiegeszites 2023-08-13: mar az URL-requestet is feldolgozom.
    */
    public static function getProcessedRequestData()
    {
        $scaleFromUrl = self::getScaleFromUrl();
        $scaleId = StringHelper::mendValue(App::getContainer()->getRequest()->get('scale'));
        $subject = StringHelper::mendValue(App::getContainer()->getRequest()->get('subject'));
        $unitId = StringHelper::mendValue(App::getContainer()->getRequest()->get('unitId'));
        $targetUnitId = StringHelper::mendValue(App::getContainer()->getRequest()->get('targetUnitId'));
        $parentType = StringHelper::mendValue(App::getContainer()->getRequest()->get('parentType'));
        $parentId = StringHelper::mendValue(App::getContainer()->getRequest()->get('parentId'));
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
            'scaleId' => $scaleId,
            'subject' => $subject,
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

        foreach ($processedURLRequest as $key => $value) {
            if (array_key_exists($key, $rawRequestData)) {
                $rawRequestData[$key] = $value;
            }
        }

        // dump($processedURLRequest);
        // dump($rawRequestData);//exit;

        if (!$scaleFromUrl || $scaleFromUrl->getId() != $scaleId) {
            $requestData = self::nullRequestData($rawRequestData);
            $requestData['errorMessage'] = 'scale.is.invalid';
            return $requestData;
        }

        dump($rawRequestData);

        $requestData = self::secureCheckAndRequests($rawRequestData);

        return $requestData;
    }

    public static function secureCheckAndRequests($requestData)
    {
        if ($requestData['scaleId']) {
            App::getContainer()->wireService('projects/ASC/repository/AscScaleRepository');
            $ascScaleRepo = new AscScaleRepository();
            $ascScale = $ascScaleRepo->find($requestData['scaleId']);

            if ($ascScale) {
                $scaleIsAllowed = self::isAllowedScale($ascScale) == true ? $requestData['scaleId'] : null;
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

                    if ($requestData['unitId']) {
                        $requestData = self::findAndCheckUnit($requestData['unitId'], $requestData, 'ascUnit', self::UNIT_TRANS_REF_UNIT);
                    }

                    if ($requestData['parentId']) {
                        $requestData = self::findAndCheckUnit($requestData['parentId'], $requestData, 'parentAscUnit', self::UNIT_TRANS_REF_PARENT);
                    }

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

    public static function findAndCheckUnit($unitId, array $requestData, string $objectIndex, string $unitTransRef = self::UNIT_TRANS_REF_UNIT, array $acceptedNonDatabaseUnitIdValues = [])
    {
        if (in_array($unitId, $acceptedNonDatabaseUnitIdValues)) {
            return $requestData;
        }

        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $ascUnitRepo = new AscUnitRepository();

        $ascUnit = $ascUnitRepo->find($unitId);
        if (!$ascUnit) {
            $requestData = self::nullRequestData($requestData);
            $requestData['errorMessage'] = $unitTransRef.'.is.invalid';
        } else {
            $ascUnitIsAllowed = self::isAllowedUnit($ascUnit);
            if (!$ascUnitIsAllowed) {
                $requestData = self::nullRequestData($requestData);
                $requestData['errorMessage'] = $unitTransRef.'.is.unauthorized';
                return $requestData;
            } else {
                $requestData[$objectIndex] = $ascUnit;
            }
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

    public static function isAllowedScale(AscScale $ascScale, $ownerOnly = false)
    {
        App::getContainer()->wireService('projects/ASC/entity/AscScale');

        return ($ascScale->getUserAccount() && $ascScale->getUserAccount()->getId() == App::getContainer()->getUser()->getUserAccount()->getId()) ? true : false;
    }

    public static function isAllowedUnit(AscUnit $ascUnit)
    {
        App::getContainer()->wireService('projects/ASC/entity/AscUnit');
        $ascScale = $ascUnit->getAscScale();
        if (!$ascScale) {
            return false;
        }

        return self::isAllowedScale($ascScale);
    }

    public static function getScaleFromUrl()
    {
        if (isset(self::$cache['scale'])) {
            return self::$cache['scale'];
        }
        $url = App::getContainer()->getUrl()->getParamChain();
        $urlParts = explode('/', $url);
        $scaleFound = false;
        $scaleId = null;
        $scale = null;
        foreach ($urlParts as $urlPart) {
            if ($scaleFound) {
                $scaleId = $urlPart;
                $scaleFound = false;
                break;
            }
            if ($urlPart == 'scale') {
                $scaleFound = true;
            }
        }

        App::getContainer()->wireService('projects/ASC/service/AscUnitBuilderService');
        // $ascUnitBuilderService = new AscUnitBuilderService();

        if ($scaleId && is_numeric($scaleId)) {
            $scale = AscUnitBuilderService::getScale((int)$scaleId);
            if ($scale) {
                self::$cache['scale'] = $scale;
            }
        }
        // dump($scaleId);exit;
        
        return $scale;
    }

    public static function getSubjectFromUrl()
    {
        if (isset(self::$cache['subject'])) {
            return self::$cache['subject'];
        }
        $url = App::getContainer()->getUrl()->getParamChain();
        $urlParts = explode('/', $url);
        $subjectFound = false;
        $subject = null;
        foreach ($urlParts as $urlPart) {
            if ($subjectFound) {
                $subject = $urlPart;
                $subjectFound = false;
                break;
            }
            if ($urlPart == 'subject') {
                $subjectFound = true;
            }
        }

        if ($subject) {
            self::$cache['subject'] = $subject;
        }
        
        return $subject;
    }

    public static function getJuxtaposedSubjectFromUrl()
    {
        if (isset(self::$cache['juxtaposedSubject'])) {
            return self::$cache['juxtaposedSubject'];
        }
        $url = App::getContainer()->getUrl()->getParamChain();
        $urlParts = explode('/', $url);
        $subjectFound = false;
        $subject = null;
        foreach ($urlParts as $urlPart) {
            if ($subjectFound) {
                $subject = $urlPart;
                $subjectFound = false;
                break;
            }
            if ($urlPart == 'juxtaposedSubject') {
                $subjectFound = true;
            }
        }

        if ($subject) {
            self::$cache['juxtaposedSubject'] = $subject;
        }
        
        return $subject;
    }
}