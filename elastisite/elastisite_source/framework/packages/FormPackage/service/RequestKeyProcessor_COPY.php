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
 * @param originChain: person_address_1
 * @param propertyAlias: country
*/
class RequestKeyProcessor_COPY extends Service
{
    const HIDE_SINGLE_ENTITIES = true;

    /**
     * input
     * The original requestKey
    */
    private $requestKey;

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
    // private $requestKeyAttributes = array(
    //     'requestKeyPrefix' => null,
    //     'propertyIdentifier' => null,
    //     'originChain' => null,
    //     'propertyAlias' => null
    // );

    /**
     * output
    */
    private $requestKeyPrefix;

    /**
     * output
    */
    private $propertyIdentifier;

    /**
     * output
    */
    private $originChain;

    /**
     * output
    */
    private $propertyAlias;

    /**
     * output
    */
    private $requestKeyPattern;

    /**
     * output
    */
    private $ancestoryArray;

    /**
     * output
    */
    private $errors = [];

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

    public function __construct($requestKey, $form)
    {
        $this->requestKey = $requestKey;
        $this->packageName = $form->getPackageName();
        $this->subject = $form->getSubject();
        $this->specsMap = $form->getSpecsMap();
        $this->entityMap = $form->getEntityMap();
        // $this->form = $form;
        $this->createRequestKeyPrefix();
        $this->createRequestKeyAttributes();
        $this->createRequestKeyPattern();
    }

    private function createRequestKeyPrefix()
    {
        $packageName = $this->packageName;
        $subject = $this->subject;
        $packageNameStr = $packageName ? $packageName : '';
        $subjectStr = ($packageNameStr == '' ? '' : '_').($subject ? $subject : '');
        $this->requestKeyPrefix = $packageNameStr.$subjectStr == '' ? '' : $packageNameStr.$subjectStr.'_';
    }

    public function createRequestKeyAttributes()
    {
        // $packageName = $this->form->getPackageName();
        // $subject = $this->form->getSubject();
        $requestKeyErrors = array();
        $requestKeyParts = explode('_', $this->requestKey);

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

        if ($this->subject && $this->packageName && (!in_array($this->subject, [$requestKeyParts[1], $requestKeyParts[0]]))) {
            $this->errors[] = 'INVALID_SUBJECT_IN_REQUEST_KEY:'.$requestKeyParts[0].','.$requestKeyParts[1];
            // return false;
        }

        $propertyIdentifier = trim(substr($this->requestKey, strlen(trim($this->requestKeyPrefix, '_'))), '_');
        $propertyIdentifierParts = explode('_', $propertyIdentifier);
        if (count($propertyIdentifierParts) == 1) {
            $originChain = '';
            $propertyAlias = $propertyIdentifier;
        } else {
            $originChain = BasicUtils::explodeAndRemoveElement($propertyIdentifier, '_', 'last');
            $propertyAlias = BasicUtils::explodeAndGetElement($propertyIdentifier, '_', 'last');
        }

        $this->propertyIdentifier = $propertyIdentifier;
        $this->originChain = $originChain;
        $this->propertyAlias = $propertyAlias;
    }

    public function createRequestKeyPattern()
    {
        $entityKeyParts = explode('-', $this->specsMap[$this->propertyAlias]['entityKey']);
        // dump($entityKeyParts[1]);
        $this->ancestoryArray = $this->getAncestoryArray($entityKeyParts[1]);
        // dump($ancestoryArray);
        $ancestoryPattern = self::getOriginPattern($this->ancestoryArray);
        $requestKeyPattern = trim($this->requestKeyPrefix.$ancestoryPattern, '_').'_'.$this->propertyAlias;
        // dump($requestKeyPattern);exit;
        $this->requestKeyPattern = $requestKeyPattern;
    }

    // public static function getRequestKeyPattern($propertyAlias, $form)
    // {
    //     // $pattern = null;
    //     $prefix = self::getPrefix($form);
        
    //     $entityKeyParts = explode('-', $form->getSpecsMap()[$propertyAlias]['entityKey']);
    //     // dump($entityKeyParts[1]);
    //     $ancestoryArray = self::getAncestoryArray($entityKeyParts[1], $form);
    //     // dump($ancestoryArray);
    //     $ancestoryPattern = self::getOriginPattern($ancestoryArray);
    //     $requestKeyPattern = trim($prefix.$ancestoryPattern, '_').'_'.$propertyAlias;
    //     // dump($requestKeyPattern);exit;
    //     return $requestKeyPattern;
    // }

    /**
     * Get all, except the PrimaryEntity
    */
    public function getAncestoryArray($childEntityName, $ancestoryArray = array())
    {
        $reverseAncestoryArray = self::getReverseAncestoryArray($childEntityName);
        return array_reverse($reverseAncestoryArray);
    }

    public function getReverseAncestoryArray($childEntityName, $ancestoryArray = array())
    {
        $counter = count($ancestoryArray);
        $ancestoryArray[$counter]['entityName'] = $childEntityName;
        $ancestorFound = false;
        // dump($form->getEntityMap());
        foreach ($this->entityMap as $entityKey => $specs) {
            $entityKeyParts = explode('-', $entityKey);
            // dump($entityKeyParts[1]);
            // dump('**'.$entityKeyParts[1].'-'.$childEntityName.':'.$entityKeyParts[0]);
            if ($entityKeyParts[1] == $childEntityName) {
                if (isset($this->entityMap[$entityKey]['multipleChild'])) {
                    // dump($entityKey);
                    // dump($form->getEntityMap());
                    // dump($form->getEntityMap()[$entityKey]);
                    $multiple = $this->entityMap[$entityKey]['multipleChild'];
                    $ancestorFound = true;
                    $ancestoryArray[$counter]['multiple'] = $multiple;
                    if ($childEntityName != $entityKeyParts[0]) {
                        $ancestoryArray = self::getReverseAncestoryArray($entityKeyParts[0], $ancestoryArray);
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

    public static function getOriginPattern($ancestoryArray)
    {
        $ancestoryPattern = '';
        foreach ($ancestoryArray as $ancestoryArrayElement) {
            if (!self::HIDE_SINGLE_ENTITIES || (self::HIDE_SINGLE_ENTITIES && $ancestoryArrayElement['multiple'])) {
                $ancestoryPattern .= '_'.$ancestoryArrayElement['entityName'];
            }
            if ($ancestoryArrayElement['multiple']) {
                $ancestoryPattern .= '_*'.$ancestoryArrayElement['entityName'].'-counter*';
            }
        }
        return trim($ancestoryPattern, '_');
    }

    // UserPackage_userRegistration_Person_Address_*Address-counter*_floor
    public static function getRequestKeyProperties($requestKey)
    {
        $params = array();
        $requestKeyParts = explode('_', $requestKey);
        for ($i = 0; $i < count($requestKeyParts); $i++) {
            if (is_numeric($requestKeyParts[$i])) {
                $counterString = '*'.$requestKeyParts[$i - 1].'-counter*';
                $params[$counterString] = $requestKeyParts[$i];
                $requestKeyParts[$i] = $counterString;
            }
        }
        return array(
            'pattern' => implode('_', $requestKeyParts),
            'params' => $params
        );
    }

    // public static function concatRequestKey($packageName, $subject, $originChain, $propertyAlias)
    // {
    //     $requestKey = '';

    //     if ($packageName && $packageName != '') {
    //         $requestKey .= $packageName.'_';
    //     }

    //     if ($subject && $subject != '') {
    //         $requestKey .= $subject;
    //     }

    //     if ($originChain && $originChain != '') {
    //         $requestKey .= $requestKey == '' ? '' : '_';
    //         $requestKey .= $originChain;
    //     }

    //     $requestKey .= $requestKey == '' ? '' : '_';
    //     $requestKey .= $propertyAlias;

    //     // dump($requestKey);
    //     return $requestKey;
    // }
}