<?php
namespace framework\packages\FormPackage\service;

use framework\component\parent\Service;
// use framework\component\exception\ElastiException;
use framework\kernel\utility\BasicUtils;
use framework\packages\FormPackage\entity\Form;

/**
 * @param requestKey: UserPackage_edit_person_address_1_country
 * @param propertyIdentifier: person_address_1_country
 * @param requestKeyPrefix: UserPackage_edit
 * @param packageName: UserPackage
 * @param subject: edit
 * Renamed! @param originChain: person_address_1
 * @param ancestoryKey: person_address_1
 * @param propertyAlias: country
 * @param fullPropertyPrefix: UserPackage_edit_person_address_1
*/
class RequestKeyProcessor extends Service
{
    const PROPERTY_IDENTIFIER_SEPARATOR = '_';

    // const ORIGIN_CHAIN_ENTITY_SEPARATOR = '_';

    // const ORIGIN_CHAIN_COUNTER_OUTER_SEPARATOR = '-';

    const ANCESTORY_KEY_COUNTER_INNER_SEPARATOR = '.';

    const HIDE_SINGLE_ENTITIES = true;

    public $line;

    public $debug = false;

    /**
     * input
    */
    public $postedRequestKey;

    /**
     * input
    */
    private $externalPost = false;

    /**
     * output
    */
    public $requestKey;

    /**
     * input OR output
    */
    public $propertyAlias;

    public $property;

    /**
     * input
    */
    private $packageName;

    /**
     * input
    */
    private $subject;

    /**
     * output
    */
    public $requestKeyPrefix;

    /**
     * input
    */
    private $postedPropertyIdentifier;

    /**
     * output
    */
    public $propertyIdentifier;

    /**
     * output
    */
    public $postedAncestoryKey;

    /**
     * output
    */
    public $ancestoryKey;

    /**
     * output
    */
    // private $requestKeyOriginMap;

    /**
     * output
    */
    public $entityKey;

    /**
     * output
    */
    public $entityKeyParts;

    /**
     * output
    */
    public $ancestoryMap;

    /**
     * output
    */
    public $ancestoryRequestParams;

    /**
     * output
    */
    public $requestKeyPattern;

    /**
     * output
    */
    public $ancestoryPattern;

    /**
     * output
     * 
     * A requestKey teljes propertyAlias előtti része, vagyis a packageName, a subject és az ancestoryKey.
    */
    public $fullPropertyPrefix;

    /**
     * output
    */
    public $errors = [];

    /**
     * input
    */
    private $externalPosts = [];

    /**
     * input
    */
    private $specsMap;

    /**
     * input
    */
    private $entityMap;

    /**
     * input
     * @var Form
    */
    // private $form;

    public function __construct($form)
    {
        // dump($form);
        // $this->requestKey = $requestKey;
        $this->packageName = $form->getPackageName();
        $this->subject = $form->getSubject();
        $this->specsMap = $form->getSpecsMap();
        $this->entityMap = $form->getEntityMap();
        $this->externalPosts = $form->getExternalPosts();
        // $this->form = $form;
    }

    public function setPostedRequestKey($postedRequestKey)
    {
        $this->postedRequestKey = $postedRequestKey;
    }

    public function setPropertyAlias($propertyAlias)
    {
        $this->propertyAlias = $propertyAlias;
    }

    public function isExternalPost()
    {
        return $this->externalPost;
    }

    public function createFullPropertyPrefix()
    {
        $fullPropertyPrefix = rtrim($this->requestKey, $this->propertyAlias);
        $fullPropertyPrefix = trim($fullPropertyPrefix, '_');
        $this->fullPropertyPrefix = $fullPropertyPrefix;
    }

    public function process()
    {
        if (is_array($this->externalPosts) && in_array($this->postedRequestKey, $this->externalPosts)) {
            $this->externalPost = true;
            return false;
        }
        $this->createRequestKeyPrefix();
        if ($this->postedRequestKey) {
            $this->processPostedRequestKey();
        } else {
            // dump($this->propertyAlias);
            // $this->property = $this->specsMap[$this->propertyAlias]['property'];
        }
        $entityKeyCreated = $this->createEntityKey();
        if (!$entityKeyCreated) {
            return false;
        }
        $this->createEntityKeyParts();
        $this->createAncestoryMap();
        $this->createAncestoryPattern();
        $this->createAncestoryRequestParams();
        $this->createAncestoryKey();
        $this->createPropertyIdentifier();
        $this->createRequestKeyPattern();
        //$this->createPropertyIdentifier();
        // if (!$this->postedRequestKey) {
        //     $this->createRequestKey();
        // }
        $this->createRequestKey();
        $this->createFullPropertyPrefix();
        $this->property = $this->specsMap[$this->propertyAlias]['property'];

        $this->specsMap = null;
        $this->entityMap = null;

        // $this->changePropertyAliasToProperty();
        // if ($this->postedRequestKey) {
        //     $this->createRequestKeyOriginMap();
        // }
        // if ($this->propertyAlias == 'temporaryPersonName') {
        //     dump($this);
        // }
        // dump($this);
    }

    private function createRequestKeyPrefix()
    {
        $packageName = $this->packageName;
        $subject = $this->subject;
        $packageNameStr = $packageName ? $packageName : '';
        $subjectStr = ($packageNameStr == '' ? '' : '_').($subject ? $subject : '');
        $this->requestKeyPrefix = $packageNameStr.$subjectStr == '' ? '' : $packageNameStr.$subjectStr.'_';
    }

    private function createEntityKey()
    {
        // if (!isset($this->specsMap[$this->propertyAlias]['entityKey'])) {
        //     dump($this->externalPosts);
        //     dump($this);
        // }
        if (!isset($this->specsMap[$this->propertyAlias]['entityKey'])) {
            return false;
            // dump($this->specsMap);
            // dump($this->propertyAlias);
            // dump($this);
        }
        $this->entityKey = $this->specsMap[$this->propertyAlias]['entityKey'];
        return true;
    }

    private function createEntityKeyParts()
    {
        $this->entityKeyParts = explode('-', $this->entityKey);
    }

    public function createRequestKeyPattern()
    {
        // if ($this->propertyAlias == 'temporaryPersonName') {
        //     // dump(trim($this->requestKeyPrefix.$this->ancestoryPattern, '_'));
        // }
        $requestKeyPattern = trim($this->requestKeyPrefix.$this->ancestoryKey, '_').'_'.$this->propertyAlias;
        // dump($requestKeyPattern);exit;
        $this->requestKeyPattern = $requestKeyPattern;
    }

    public function processPostedRequestKey()
    {
        // $packageName = $this->form->getPackageName();
        // $subject = $this->form->getSubject();
        // $requestKeyErrors = array();
        $requestKeyParts = explode('_', $this->postedRequestKey);

        /**
         * This is an error.
        */
        if ($this->packageName && count($requestKeyParts) == 1) {
            $this->errors[] = 'SUBJECT_OR_PACKAGE_NAME_MISSING_FROM_REQUEST_KEY';
            // return false;
        }

        if ($this->packageName && $this->packageName != $requestKeyParts[0]) {
            $this->errors[] = 'PACKAGE_NAME_MISSING_FROM_REQUEST_KEY';
            // return false;
        }

        if (!isset($requestKeyParts[1])) {
            // dump($this);
            return false;
        }

        if ($this->subject && $this->packageName && (!in_array($this->subject, [$requestKeyParts[1], $requestKeyParts[0]]))) {
            $this->errors[] = 'INVALID_SUBJECT_IN_REQUEST_KEY:'.$requestKeyParts[0].','.$requestKeyParts[1];
            // return false;
        }

        $postedPropertyIdentifier = trim(substr($this->postedRequestKey, strlen(trim($this->requestKeyPrefix, '_'))), '_');
        $propertyIdentifierParts = explode(self::PROPERTY_IDENTIFIER_SEPARATOR, $postedPropertyIdentifier);
        if (count($propertyIdentifierParts) == 1) {
            $postedAncestoryKey = '';
            $propertyAlias = $postedPropertyIdentifier;
        } else {
            $postedAncestoryKey = BasicUtils::explodeAndRemoveElement($postedPropertyIdentifier, '_', 'last');
            $propertyAlias = BasicUtils::explodeAndGetElement($postedPropertyIdentifier, '_', 'last');
        }

        $this->postedPropertyIdentifier = $postedPropertyIdentifier;
        $this->postedAncestoryKey = $postedAncestoryKey;
        $this->propertyAlias = $propertyAlias;

        // if ($this->postedAncestoryKey) {
        //     dump($this->postedRequestKey);
        //     dump($this->postedAncestoryKey);
        // }
        // if (!$this->postedAncestoryKey && $this->debug) {
        //     dump($postedPropertyIdentifier);
        //     dump($postedAncestoryKey);
        // }

        // if (!$this->propertyAlias) {
        //     $this->propertyAlias = $propertyAlias;
        // }

        // if (!isset($this->specsMap[$this->propertyAlias])) {
        //     dump('-------');
        //     dump('PropAlias: '.$this->propertyAlias);
        //     dump($this);exit;
        // }
        // $this->property = $this->specsMap[$this->propertyAlias]['property'];
        // // dump($this->property);
        // if (!$this->property) {
        //     dump($this->specsMap[$this->propertyAlias]);
        // }
    }

    private function changePropertyAliasToProperty()
    {
        $this->requestKey = BasicUtils::explodeAndRemoveElement($this->requestKey, '_', 'last').'_'.$this->property;
    }

    private function createAncestoryMap()
    {
        // $this->ancestoryMap = $this->getAncestoryArray($this->entityKeyParts[1]);
        // $reverseAncestoryArray = self::getAncestoryMap($this->entityKeyParts[1]);
        // dump($this->entityMap);
        $this->ancestoryMap = self::getAncestoryMap($this->entityKeyParts[1], $this->entityMap);
    }

    public static function getAncestoryMap($childEntityName, $entityMap)
    {
        $reverseAncestoryArray = self::getReverseAncestoryMap($childEntityName, $entityMap);
        return array_reverse($reverseAncestoryArray);
    }

    // private function getAncestoryArray($childEntityName)
    // {
    //     $reverseAncestoryArray = self::getReverseAncestoryArray($childEntityName);
    //     return array_reverse($reverseAncestoryArray);
    // }

    public static function getReverseAncestoryMap($childEntityName, $entityMap, $ancestoryArray = array())
    {
        $counter = count($ancestoryArray);
        $ancestoryArray[$counter]['entityName'] = $childEntityName;
        $ancestorFound = false;
        // dump($form->getEntityMap());

        foreach ($entityMap as $entityKey => $specs) {
            $entityKeyParts = explode('-', $entityKey);
            // dump($entityKeyParts[1]);
            // dump('**'.$entityKeyParts[1].'-'.$childEntityName.':'.$entityKeyParts[0]);
            if ($entityKeyParts[1] == $childEntityName) {
                if (isset($entityMap[$entityKey]['multipleChild'])) {
                    // dump($entityKey);
                    // dump($form->getEntityMap()[$entityKey]);
                    // dump($entityMap[$entityKey]);
                    $multiple = $entityMap[$entityKey]['multipleChild'];
                    $ancestorFound = true;
                    // $ancestoryArray[$counter]['class'] = $class;
                    $ancestoryArray[$counter]['entityKey'] = $entityKey;
                    $ancestoryArray[$counter]['multiple'] = $multiple;
                    // $ancestoryArray[$counter]['repository'] = $object->getRepository();
                    // $ancestoryArray[$counter]['class'] = $entityMap[$entityKey]['targetClass'];
                    if ($childEntityName != $entityKeyParts[0]) {
                        $ancestoryArray = self::getReverseAncestoryMap($entityKeyParts[0], $entityMap, $ancestoryArray);
                    }
                } else {
                    // $multiple = false;
                }
            }
            // $parentEntityName = $entityKeyParts[0];
            // $childEntityName = $entityKeyParts[1];
        }
        if (!$ancestorFound) {
            unset($ancestoryArray[$counter]);
        }
        return $ancestoryArray;
    }

    public static function findEntityParamsOnMap($entityKeyRequest, $entityMap)
    {
        foreach ($entityMap as $entityKey => $specs) {
            // $entityKeyParts = explode('-', $entityKey);
            if ($entityKey == $entityKeyRequest) {
                $class = $entityMap[$entityKey]['childRelation']['targetClass'];
                $classPath = str_replace('\\', '/', $class);
                $container = \framework\kernel\base\Container::getSelfObject();
                $container->wireService($classPath);
                $object = new $class();
                $repository = $object->getRepository();
                return [
                    'entityKey' => $entityKey,
                    'repository' => $repository
                ];
            }
        }
        return null;
    }

    /*
    Example: Address_*Address.counter*

    */
    public function createAncestoryPattern()
    {
        $ancestoryPattern = '';
        $loopCounter = 0;
        foreach ($this->ancestoryMap as $ancestoryMapElement) {
            if (!self::HIDE_SINGLE_ENTITIES || (self::HIDE_SINGLE_ENTITIES && $ancestoryMapElement['multiple'])) {
                $ancestoryPattern .= ($loopCounter > 0 ? self::PROPERTY_IDENTIFIER_SEPARATOR : '').''.$ancestoryMapElement['entityName'];
            }
            if ($ancestoryMapElement['multiple']) {
                $ancestoryPattern .= self::PROPERTY_IDENTIFIER_SEPARATOR.'*'.$ancestoryMapElement['entityName'].self::ANCESTORY_KEY_COUNTER_INNER_SEPARATOR.'counter*';
            } else {
                $ancestoryPattern .= ($loopCounter == 0 ? '' : '_').$ancestoryMapElement['entityName'];
            }
            $loopCounter++;
        }
        $ancestoryPattern = trim($ancestoryPattern, '_');
        $this->ancestoryPattern = $ancestoryPattern;
    }

    private function getAncestoryRequestParams()
    {

    }

    private function createAncestoryRequestParams()
    {
        // if ($this->postedAncestoryKey) {
        //     $postedAncestoryKeyParts = explode(self::PROPERTY_IDENTIFIER_SEPARATOR, $this->postedAncestoryKey);
        //     foreach ($this->ancestoryMap as $ancestoryMapElement) {
        //         $childCounter = 0;
        //         for ($i = 0; $i < count($postedAncestoryKeyParts); $i++) {
        //             $postedAncestoryKeyPart = $postedAncestoryKeyParts[$i];
        //             if ($ancestoryMapElement['entityName'] == $postedAncestoryKeyPart) {
        //                 $potentialCounterIndex = $i + 1;
        //                 if (isset($postedAncestoryKeyParts[$potentialCounterIndex]) && is_numeric($postedAncestoryKeyParts[$potentialCounterIndex])) {
        //                     $childCounter = $postedAncestoryKeyParts[$potentialCounterIndex];
        //                 }
        //             }
        //         }
        //         if ($ancestoryMapElement['multiple'] == false && $childCounter > 0) {
        //             $childCounter = 0;
        //             $this->errors[] = 'ANCESTORY_COUNTER_CANNOT_BE_HIGHER_THAN_ZERO_IF_ENTITY_IS_NOT_MULTIPLE: '.$ancestoryMapElement['entityName'];
        //         }
        //         $this->ancestoryRequestParams[] = [
        //             'entityName' => $ancestoryMapElement['entityName'],
        //             'childCounter' => $childCounter
        //         ];
        //     }
        // }
        // dump($this->postedAncestoryKey);
        $ancestoryKey = '';
        if (!$this->postedAncestoryKey) {
            // dump($this->ancestoryMap);
            // dump($this->ancestoryPattern);
            // $ancestoryKeyArray = [];
            // foreach ($this->ancestoryMap as $ancestoryMapElement) {
            //     $ancestoryKeyArray[] = $ancestoryMapElement['entityName'];
            //     if ($ancestoryMapElement['multiple']) {
            //         $ancestoryKeyArray[] = '0';
            //     }
            // }
            // $ancestoryKey = implode('_', $ancestoryKeyArray);
            // dump($ancestoryKey);
            // dump($this);
        } else {
            $ancestoryKey = $this->postedAncestoryKey;
        }

        $postedAncestoryKeyParts = explode(self::PROPERTY_IDENTIFIER_SEPARATOR, $ancestoryKey);
        foreach ($this->ancestoryMap as $ancestoryMapElement) {
            $childCounter = 0;
            for ($i = 0; $i < count($postedAncestoryKeyParts); $i++) {
                $postedAncestoryKeyPart = $postedAncestoryKeyParts[$i];
                if ($ancestoryMapElement['entityName'] == $postedAncestoryKeyPart) {
                    $potentialCounterIndex = $i + 1;
                    if (isset($postedAncestoryKeyParts[$potentialCounterIndex]) && is_numeric($postedAncestoryKeyParts[$potentialCounterIndex])) {
                        $childCounter = $postedAncestoryKeyParts[$potentialCounterIndex];
                    }
                }
            }
            if ($ancestoryMapElement['multiple'] == false && $childCounter > 0) {
                $childCounter = 0;
                $this->errors[] = 'ANCESTORY_COUNTER_CANNOT_BE_HIGHER_THAN_ZERO_IF_ENTITY_IS_NOT_MULTIPLE: '.$ancestoryMapElement['entityName'];
            }
            $this->ancestoryRequestParams[] = [
                'entityName' => $ancestoryMapElement['entityName'],
                'childCounter' => $childCounter
            ];
        }
    }

    private function createAncestoryKey()
    {
        $resultArray = [];
        for ($i = 0; $i < count($this->ancestoryMap); $i++) {
            $ancestoryMapElement = $this->ancestoryMap[$i];
            if ($ancestoryMapElement['multiple']) {
                if (!$this->ancestoryRequestParams) {
                    dump($this->ancestoryMap);
                    dump($this); 
                    //exit;
                }
                $resultArray[] = $ancestoryMapElement['entityName'].self::PROPERTY_IDENTIFIER_SEPARATOR.$this->ancestoryRequestParams[$i]['childCounter'];
            } 
            else {
                $resultArray[] = $ancestoryMapElement['entityName'];
            }
        }
        $this->ancestoryKey = implode(self::PROPERTY_IDENTIFIER_SEPARATOR, $resultArray);
    }

    private function createPropertyIdentifier()
    {
        $propertyIdentifier = (string)$this->ancestoryKey;
        // dump($propertyIdentifier);
        $propertyIdentifier = $propertyIdentifier.($propertyIdentifier != '' ? self::PROPERTY_IDENTIFIER_SEPARATOR : '').$this->propertyAlias;
        $this->propertyIdentifier = $propertyIdentifier;
    }

    private function createRequestKey()
    {
        $this->requestKey = $this->requestKeyPrefix.$this->propertyIdentifier;
    }

    // public function getRequestKeyPattern()
    // {
    //     return $this->requestKeyPattern;
    // }
}