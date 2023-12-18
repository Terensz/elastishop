<?php
namespace framework\kernel\EntityManager\entity;

use framework\component\exception\ElastiException;
use framework\kernel\component\Kernel;

class EntityCollector extends Kernel
{
    const GENERAL_ERROR = 'Error';

    private $collectionIds = array();
    private $collection = array();
    private $propertyDetailsCollection = array();

    public function setCollection($collection)
    {
        // if (!is_array($collection)) {
        //     dump($collection);
        // }

        if (!is_array($collection)) {
            dump($collection);
            dump($this);exit;
        }
        foreach ($collection as $collectionElement) {
            $this->collectionIds[] = $collectionElement['id'];
        }
        $this->collection = $collection;
    }

    public function getMaxId()
    {
        $max = 0;
        foreach ($this->collection as $element) {
            if ($element['collectionId'] > $max) {
                $max = $element['collectionId'];
            }
        }
        return $max;
    }

    public function merge($newCollectionPart, $newBaseCollectionId, $newBaseParentId)
    {
        // dump($newCollectionPart);
        $collectionIdCounter = $this->getMaxId();
        // dump('$collectionIdCounter: '.$collectionIdCounter);
        $arrangedNewCollectionPart = array();
        $loopCounter = 0;
        $parentIds = array();
        foreach ($newCollectionPart as $index => $newCollectionPartElement) {
            $collectionIdCounter++;
            $originalCollectionId = $newCollectionPartElement['collectionId'];
            // if ($loopCounter > 0) {
            //     $collectionIdCounter++;
            //     $newCollectionPartElement['collectionId'] = $collectionIdCounter;
            // }
            // $newCollectionPartElement['collectionId'] = $loopCounter == 0 ? $newCollectionPartElement['collectionId'] : $collectionIdCounter;
            // $parentIndexes[] = array($index => $newCollectionPartElement['collectionId']);
            if ($loopCounter == 0) {
                $newCollectionPartElement['collectionId'] = $newBaseCollectionId;
                $newCollectionPartElement['parentId'] = $newBaseParentId;
                // $newCollectionPartElement['collectionKey'] = $newCollectionPartElement['parentId'].'-'.$newCollectionPartElement['childCounter'];
            } else {
                // dump('$parentIds:');
                // dump($parentIds);
                $newCollectionPartElement['collectionId'] = $collectionIdCounter;
                // $newCollectionPartElement['collectionKey'] = $newCollectionPartElement['parentId'].'-'.$newCollectionPartElement['childCounter'];
                if (in_array($newCollectionPartElement['parentId'], array_keys($parentIds))) {
                    $newCollectionPartElement['parentId'] = $parentIds[$newCollectionPartElement['parentId']];
                }
                // $parentIndexes[] = array($index => $newCollectionPartElement['collectionId']);
            }
            $newCollectionPartElement['collectionKey'] = $newCollectionPartElement['parentId'].'-'.$newCollectionPartElement['childCounter'];
            $parentIds[$originalCollectionId] = $newCollectionPartElement['collectionId'];
            $arrangedNewCollectionPart[] = $newCollectionPartElement;
            $loopCounter++;
        }
        // dump($parentIds);
        $this->collection = array_merge($this->collection, $arrangedNewCollectionPart);
    }

    public function modifyEntity($collectionId, $property, $newValue)
    {
        $index = $this->find($collectionId);
        $entity = $this->collection[$index]['entity'];
        $entity->set($property, $newValue);
        $this->collection[$index]['entity'] = $entity;
    }

    public function find($collectionId, $newCollection = null)
    {
        $collection = $newCollection ? $newCollection : $this->collection;
        foreach ($collection as $index => $element) {
            if ($element['collectionId'] == $collectionId) {
                return $index;
            }
        }
        return false;
    }

    public function isMultiple($parentId, $entityAlias, $entityName)
    {
        // dump($parentId);
        // dump($entityName);
        if ($parentId == 0) {
            # Toplevel, not multiple
            return false;
        }
        $parentIndex = $this->find($parentId);
        // dump($parentIndex);
        // dump($this->collection);
        $parentEntity = $this->collection[$parentIndex]['entity'];
        // $parentEntity = $this->find($parentId)['entity'];
        if (!isset($parentEntity->getEntityAttributes()['relations'][$entityAlias])) {
            // dump('== Hiba!!! ==');
            // dump('entityName: '.$entityName);
            // dump($parentEntity->getEntityAttributes());
            return self::GENERAL_ERROR;
        }

        return $parentEntity->getEntityAttributes()['relations'][$entityAlias]['multiple'];
	}

    public function addElement(
        $id, 
        string $entityKey, 
        $parentId, 
        $parentEntityKey, 
        $entity,
        $entityAlias,
        $isDummyEntity = false, 
        $test = false
    )
    {
        // foreach ($this->collection as $collectionElement) {
        //     // 
        //     if (($collectionElement['id'] && $collectionElement['parentId']) && ($collectionElement['id'] == $id && $collectionElement['parentId'] == $parentId)) {
        //         dump('EntityCollector Add failed: ');
        //         // dump($id);
        //         dump('entityKey: ' . $entityKey);
        //         dump('id: ' . $collectionElement['id']);
        //         dump('parentId: ' . $collectionElement['parentId']);
        //         // dump($this->collection);
        //         return false;
        //     }
        // }


        
        // dump($entity);
        // dump("alma");exit;
        $collectionId = $this->createCollectionId();
        $childCounter = $this->createChildCounter($entityKey, $parentId);
        $collectionKey = (int)$parentId.'-'.$childCounter;
        // dump($entityKey);
        $entityNameParts = explode('-', $entityKey);
        if ($test) {
            // dump($this->isMultiple($parentId, $entityNameParts[1]));
        }

        $collectionElement = array(
            'isDummyEntity' => $isDummyEntity,
            'collectionId' => $collectionId,
            'collectionKey' => $collectionKey,
            'parentId' => $parentId,
            'parentEntityName' => $entityNameParts[0],
            'parentEntityKey' => $parentEntityKey,
            'childCounter' => $childCounter,
            'id' => $id,
            'entityName' => $entityNameParts[1],
            'entityAlias' => $entityAlias,
            'entityKey' => $entityKey,
            'multiple' => $this->isMultiple($parentId, $entityAlias, $entityNameParts[1]),
            'entity' => $entity
        );

        if ($collectionElement['multiple'] === self::GENERAL_ERROR) {
            throw new ElastiException('Error detecting isMultiple on '.$entityKey);
            // dump('Hiba!!');
            // dump($collectionElement['multiple']);
            // dump($collectionElement);
        }
        
        // dump($collectionElement);
        $this->collection[] = $collectionElement;

        if ($test) {
            dump($collectionElement);
        }

        return $collectionId;
    }

    public function sortElements()
    {
        sort($this->collection);
    }

    public function remove($collectionId)
    {
        $newCollection = array();
        foreach ($this->collection as $collectionElement) {
            if ($collectionElement['collectionId'] != $collectionId && $collectionElement['parentId'] != $collectionId) {
                $newCollection[] = $collectionElement;
            }
        }
        // sort($newCollection);
        $this->collection = $newCollection;
    }

    public function modifyCollectionElement($collectionId, $key, $newValue)
    {
        $index = $this->find($collectionId);
        $this->collection[$index][$key] = $newValue;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    // public function getRelationParams()
    // {
    //     foreach ($this->propertyDetailsCollection as $entityKey => $propertyDetails) {

    //     }
    //     return $this->relationParams;
    // }

    public function createCollectionId()
    {
        // $collectionId = count($this->collectionIds) + 1;
        // $this->collectionIds[] = $collectionId;
        return $this->getMaxId() + 1;
    }

    public function createChildCounter($entityKey, $parentId)
    {
        $counter = 0;
        // dump($this->collection );exit;
        foreach ($this->collection as $index => $collectionElement) {
            // if (!isset($collectionElement['entityKey'])) {
            //     dump('Coll. ele:');
            //     dump($collectionElement);
            // }
            if (isset($collectionElement['entityKey']) && ($collectionElement['entityKey'] == $entityKey && $collectionElement['parentId'] == $parentId)) {
                $counter++;
            }
        }
        return $counter;
    }

    // public function addEntityKey($entityKey, $collectionId)
    // {
    //     $this->entityKeys[$entityKey][] = $collectionId;
    // }
}