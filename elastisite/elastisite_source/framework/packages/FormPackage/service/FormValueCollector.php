<?php
namespace framework\packages\FormPackage\service;

use framework\component\parent\Service;
use framework\component\exception\ElastiException;
use framework\kernel\utility\BasicUtils;

class FormValueCollector extends Service
{
    private $collection = array();
    private $indexMap = array();
    private $specsMap;
    private $isStored = array();
    private $isPosted = array();

    public function __construct()
    {

        // dump($this->entitySchemas);
    }

    public function has($requestKey, $attribute)
    {
        $thisProperty = 'is'.ucfirst($attribute);
        if (in_array($requestKey, $this->$thisProperty)) {
            return true;
        }
        return false;
    }

    public function setSpecsMap($specsMap)
    {
        $this->specsMap = $specsMap;
    }

    // public function setEntityCollection($entityCollection)
    // {
    //     $this->entityCollection = $entityCollection;
    // }

    public function getCollection()
    {
        return $this->collection;
    }

    public function createIndex($entityCollectionKey, $type)
    {
        if (!isset($this->indexMap[$entityCollectionKey])) {
            $this->indexMap[$entityCollectionKey] = array();
        }
        if (!isset($this->indexMap[$entityCollectionKey][$type])) {
            $this->indexMap[$entityCollectionKey][$type] = 0;
            return 0;
        }
        $index = $this->indexMap[$entityCollectionKey][$type] + 1;
        return $index;
    }

    public function getIndex($entityCollectionKey, $type)
    {
        return $this->indexMap[$entityCollectionKey][$type];
    }

    public function hasValue($propertyAlias, $attribute, $requestKey)
    {
        return isset($this->collection[$propertyAlias][$requestKey][$attribute]) ? true : false;
    }

    public function addValue($value, $propertyAlias, $attribute, $requestKey)
    {
        // dump($value);dump($propertyAlias);dump($attribute);dump($requestKey);
        $thisProperty = 'is'.ucfirst($attribute);
        if (in_array($attribute, array('stored', 'posted')) && !in_array($requestKey, $this->$thisProperty)) {
            $this->$thisProperty[] = $requestKey;
        }
        $this->collection[$propertyAlias][$requestKey][$attribute] = $value;
    }

    public function getValue($request, $valueType)
    {
        if (!$request) {
            return null;
        }
        $requestKeyParts = explode('_', $request);
        // dump($attribute);
        // dump($request);
        // dump($this->collection[$request]);exit;
        if (count($requestKeyParts) == 1) {
            // if ($this->specsMap[$request]['multiple']) {
            //     throw new ElastiException('Bad request key ('.$request.') for multiple entity relation of '.$this->specsMap[$request]['entityKey'], ElastiException::ERROR_TYPE_SECRET_PROG);
            // }
            $result = null;
            $resultCounter = 0;
            // dump($this->collection);
            // if (!isset($this->collection[$request])) {
            //     $this->collection[$request] = array(
            //         'entityCollectionId' => null,
            //         'stored' => null,
            //         'displayed' => null,
            //         'valid' => true,
            //         'message' => null
            //     );
            //     // dump($this->collection);exit;
            //     // throw new ElastiException(
            //     //     $this->wrapExceptionParams(
            //     //         array('request' => $request, 'valueType' => $valueType)
            //     //     ), 
            //     //     1631
            //     // );
            // }
            // dump($this->collection[$request]);
            if (isset($this->collection[$request])) {
                foreach ($this->collection[$request] as $requestKey => $valueArray) {
                    // dump($valueArray);
                    $result = isset($valueArray[$valueType]) ? $valueArray[$valueType] : null;
                    $resultCounter++;
                }
            }
            if ($resultCounter > 1) {
                // dump($this->collection);
                throw new ElastiException(
                    $this->wrapExceptionParams(
                        array('request' => $request, 'valueType' => $valueType)
                    ), 
                    1630
                );
            }
            return $result;
        } else {
            $propertyAlias = BasicUtils::explodeAndGetElement($request, '_', 'last');
            // if (!$this->specsMap[$propertyAlias]['multiple']) {
            //     dump($propertyAlias);
            //     dump($this->specsMap);
            //     throw new ElastiException('Bad request key ('.$request.') for not multiple entity relation of '.$this->specsMap[$propertyAlias]['entityKey'], ElastiException::ERROR_TYPE_SECRET_PROG);
            // }
            return isset($this->collection[$propertyAlias][$request][$valueType]) 
                ? $this->collection[$propertyAlias][$request][$valueType] : null;
        }
    }

    public function getDisplayed($request)
    {
        return $this->getValue($request, 'displayed');
    }

    public function getPosted($request)
    {
        return $this->getValue($request, 'posted');
    }

    public function getStored($request)
    {
        return $this->getValue($request, 'stored');
    }

    // public function getPosted($propertyAlias, $parentIndex = 0)
    // {
    //     dump($this->collection);exit;
    //     return $this->getValue($propertyAlias, 'posted');
    // }

    // public function getStored($propertyAlias, $parentIndex = 0)
    // {
    //     return $this->getValue($propertyAlias, 'stored');
    // }

    // public function getDisplayed($propertyAlias, $parentIndex = 0)
    // {
    //     return $this->getValue($propertyAlias, 'displayed');
    // }

    // public function getValid($propertyAlias, $parentIndex = 0)
    // {
    //     return $this->getValue($propertyAlias, 'valid');
    // }

    // public function getMessage($propertyAlias, $parentIndex = 0)
    // {
    //     return $this->getValue($propertyAlias, 'message');
    // }
}
