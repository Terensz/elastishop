<?php
namespace framework\packages\FormPackage\service;

use framework\component\parent\Service;
use framework\component\exception\ElastiException;
use framework\kernel\utility\BasicUtils;

/**
 * @param requestKey: UserPackage_edit_person_address_1_country
 * @param propertyIdentifier: person_address_1_country
 * @param requestKeyPrefix: UserPackage_edit
 * @param packageName: UserPackage
 * @param subject: edit
 * @param originChain: person_address_1
 * @param propertyAlias: country
*/
class RequestKeyService extends Service
{
    const HIDE_SINGLE_ENTITIES = true;
    // public static function create($propertyAlias, $form)
    // {
    //     $requestKeyPattern = self::createAncestory($propertyAlias, $form);
    //     dump($requestKeyPattern);exit;
    // }

    // public static function getRequestKeyAttributes($requestKey, $form)
    // {
    //     $packageName = $form->getPackageName();
    //     $subject = $form->getSubject();
    //     $requestKeyErrors = array();
    //     $requestKeyParts = explode('_', $requestKey);
    //     if ($packageName && $packageName && count($requestKeyParts) == 1) {
    //         return array(
    //             'requestKeyPrefix' => null,
    //             'propertyIdentifier' => null,
    //             'originChain' => null,
    //             'propertyAlias' => null,
    //             'errors' => array('SUBJECT_OR_PACKAGE_NAME_MISSING_FROM_REQUEST_KEY')
    //         );
    //     }
    //     if ($packageName && $packageName != $requestKeyParts[0]) {
    //         $requestKeyErrors[] = 'PACKAGE_NAME_MISSING_FROM_REQUEST_KEY';
    //     }
    //     if (($subject && $packageName && $subject != $requestKeyParts[1]) 
    //     || ($subject && !$packageName && $subject != $requestKeyParts[0])) {
    //         $requestKeyErrors[] = 'INVALID_SUBJECT_IN_REQUEST_KEY:'.$requestKeyParts[0].','.$requestKeyParts[1];
    //     }
    //     $requestKeyPrefix = self::getPrefix($form);
    //     $propertyIdentifier = trim(substr($requestKey, strlen(trim($requestKeyPrefix, '_'))), '_');
    //     $propertyIdentifierParts = explode('_', $propertyIdentifier);
    //     if (count($propertyIdentifierParts) == 1) {
    //         $originChain = '';
    //         $propertyAlias = $propertyIdentifier;
    //     } else {
    //         $originChain = BasicUtils::explodeAndRemoveElement($propertyIdentifier, '_', 'last');
    //         $propertyAlias = BasicUtils::explodeAndGetElement($propertyIdentifier, '_', 'last');
    //     }

    //     $res = array(
    //         // 'requestKey' => $requestKey,
    //         'requestKeyPrefix' => $requestKeyPrefix,
    //         'propertyIdentifier' => $propertyIdentifier,
    //         'originChain' => $originChain,
    //         'propertyAlias' => $propertyAlias,
    //         'errors' => $requestKeyErrors
    //     );
    //     // dump($res);exit;
    //     return $res;
    // }

    public static function getPrefix($form)
    {
        $packageName = $form->getPackageName();
        $subject = $form->getSubject();
        $packageNameStr = $packageName ? $packageName : '';
        $subjectStr = ($packageNameStr == '' ? '' : '_').($subject ? $subject : '');
        return $packageNameStr.$subjectStr == '' ? '' : $packageNameStr.$subjectStr.'_';
    }

    public static function getRequestKeyPattern($propertyAlias, $form)
    {
        // $pattern = null;
        $prefix = self::getPrefix($form);
        // dump($form->getDummyEntities());
        // dump($form->getEntityMap());
        $entityKeyParts = explode('-', $form->getSpecsMap()[$propertyAlias]['entityKey']);
        // dump($entityKeyParts[1]);
        $ancestoryArray = self::getAncestoryArray($entityKeyParts[1], $form);
        // dump($ancestoryArray);
        $ancestoryPattern = self::getOriginPattern($ancestoryArray);
        $requestKeyPattern = trim($prefix.$ancestoryPattern, '_').'_'.$propertyAlias;
        // dump($requestKeyPattern);exit;
        return $requestKeyPattern;
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

    public static function concatRequestKey($packageName, $subject, $originChain, $propertyAlias)
    {
        $requestKey = '';

        if ($packageName && $packageName != '') {
            $requestKey .= $packageName.'_';
        }

        if ($subject && $subject != '') {
            $requestKey .= $subject;
        }

        if ($originChain && $originChain != '') {
            $requestKey .= $requestKey == '' ? '' : '_';
            $requestKey .= $originChain;
        }

        $requestKey .= $requestKey == '' ? '' : '_';
        $requestKey .= $propertyAlias;

        // dump($requestKey);
        return $requestKey;
    }
}