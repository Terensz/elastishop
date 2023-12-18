<?php
namespace framework\packages\FormPackage\entity;

use framework\component\exception\ElastiException;
use framework\component\parent\Service;
use framework\packages\FormPackage\service\FormValueCollector;
use framework\kernel\utility\BasicUtils;

class Form extends Service
{
    // private $posts;
    private $entityCollector;
    // private $relationParams;
    private $primaryRepository;
    private $entity;
    private $dummyEntities = array();
    private $entityMap = array();
    private $specsMap;
    private $type = 'edit';
    private $submitted;
    private $encodeRequestKeys;
    private $packageName;
    private $subject;
    private $valid;
    private $primaryEntityPath;
    private $primaryEntityKey;
    // private $primaryDataRepository;
    private $selectDataMethod;
    private $storeDataMethod;
    private $removeDataMethod;
    private $primaryKeyField;
    private $primaryKeyValue;
    private $valueCollector;
    private $messages;
    private $requests;
    private $externalPosts = array();
    // private $allowedPostsOutsideOfForm;
    // private $technicalProperties = array();
    private $saved = false;

    public function __construct()
    {
        // $this->posts = $this->getRequest()->getAll();
        $this->wireService('FormPackage/service/FormValueCollector');
        $this->valueCollector = new FormValueCollector();
    }

    public function getEntityName($propertyAlias)
    {
        return $this->specsMap[$propertyAlias]['entityName'];
    }

    public function getProperty($propertyAlias)
    {
        return $this->specsMap[$propertyAlias]['property'];
    }

    public function addDummyEntity($entityName, $dummyEntity)
    {
        $this->dummyEntities[$entityName] = $dummyEntity;
    }

    public function getDummyEntities()
    {
        return $this->dummyEntities;
    }

    public function getDummyEntity($entityName)
    {
        return $this->dummyEntities[$entityName];
    }

    public function createAndAddEntityMapElement($entityKey)
    {
        $entityMapElement = array();
        $entityKeyParts = explode('-', $entityKey);
        $parentEntityName = $entityKeyParts[0];
        $childEntityName = $entityKeyParts[1];
        if (isset($this->dummyEntities[$parentEntityName])) {
            $parentPropertyMap = $this->dummyEntities[$parentEntityName]->getPropertyMap();
            foreach ($parentPropertyMap as $parentPropertySpecs) {
                if (ucfirst($parentPropertySpecs['singularPropertyName']) == $childEntityName) {
                    $entityMapElement['parentEntityName'] = $parentEntityName;
                    $entityMapElement['childEntityName'] = $childEntityName;
                    $entityMapElement['multipleChild'] = $parentPropertySpecs['multiple'];
                    $entityMapElement['childRelation'] = $parentPropertySpecs['targetRelation'];
                    $entityMapElement['parentRelation'] = $parentPropertySpecs['reverseRelation'];
                }
            }

            $this->entityMap[$entityKey] = $entityMapElement;
        }
    }

    public function getEntityMap()
    {
        return $this->entityMap;
    }

    public function onEntityMap($entityKey)
    {
        return isset($this->entityMap[$entityKey]) ? true : false;
    }

    // public function setRelationParams($relationParams)
    // {
    //     $this->relationParams = $relationParams;
    // }

    // public function getRelationParams($entityKey = null)
    // {
    //     if ($entityKey) {
    //         return isset($this->relationParams[$entityKey]) ? $this->relationParams[$entityKey] : null;
    //     }
    //     return $this->relationParams;
    // }

    // public function isMultiple($entityKey)
    // {
    //     dump($entityKey);
    //     $entityKeyParts = explode('-', $entityKey);
    //     if ($entityKeyParts[0] == '0') {
    //         return false;
    //     }
    //     $relationMap = $this->entityCollector->getPropertyDetailsCollection()[$entityKey][lcfirst($entityKeyParts[0])]['relationMap'];
    //     if ($relationMap && isset($relationMap['reverseRelation']) && isset($relationMap['reverseRelation']['association'])) {
    //         return in_array($relationMap['reverseRelation']['association'], array('oneToMany', 'manyToMany')) ? true : false;
    //     } else {
    //         return false;
    //     }
    // }

    public function setEntityCollector($entityCollector)
    {
        $this->entityCollector = $entityCollector;
    }

    public function getEntityCollector()
    {
        return $this->entityCollector;
    }

    public function setPrimaryRepository($primaryRepository)
    {
        $this->primaryRepository = $primaryRepository;
    }

    public function getPrimaryRepository()
    {
        return $this->primaryRepository;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function addSpecsMap($key, $value)
    {
        return $this->specsMap[$key] = $value;
    }

    public function getSpecsMap($key = null)
    {
        return !$key ? $this->specsMap : $this->specsMap[$key];
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setSubmitted($submitted)
    {
        $this->submitted = $submitted;
    }

    public function isSubmitted()
    {
        return $this->submitted;
    }

    public function setEncodeRequestKeys($encodeRequestKeys)
    {
        $this->encodeRequestKeys = $encodeRequestKeys;
    }

    public function getEncodeRequestKeys()
    {
        return $this->encodeRequestKeys;
    }

    public function setPackageName($packageName)
    {
        $this->packageName = $packageName;
    }

    public function getPackageName()
    {
        return $this->packageName;
    }

    // public function setProjectName($projectName)
    // {
    //     $this->projectName = $projectName;
    // }

    // public function getProjectName() 
    // {
    //     return $this->projectName;
    // }

    public function setSubject($subject)
    {
        if (strpos($subject, '_') !== false) {
            throw new ElastiException('Form subject mustn\'t contain underscore character: '.$subject, ElastiException::ERROR_TYPE_SECRET_PROG);
        }
        $this->subject = $subject;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setPrimaryEntityPath($primaryEntityPath)
    {
        $this->primaryEntityPath = $primaryEntityPath;
    }

    public function getPrimaryEntityPath()
    {
        return $this->primaryEntityPath;
    }

    public function setPrimaryEntityKey($primaryEntityKey)
    {
        $this->primaryEntityKey = $primaryEntityKey;
    }

    public function getPrimaryEntityKey()
    {
        return $this->primaryEntityKey;
    }

    public function setSelectDataMethod($selectDataMethod)
    {
        $this->selectDataMethod = $selectDataMethod;
    }

    public function getSelectDataMethod()
    {
        return $this->selectDataMethod;
    }

    public function setStoreDataMethod($storeDataMethod)
    {
        $this->storeDataMethod = $storeDataMethod;
    }

    public function getStoreDataMethod()
    {
        return $this->storeDataMethod;
    }

    public function setRemoveDataMethod($removeDataMethod)
    {
        $this->removeDataMethod = $removeDataMethod;
    }

    public function getRemoveDataMethod()
    {
        return $this->removeDataMethod;
    }

    public function setPrimaryKeyField($primaryKeyField)
    {
        $this->primaryKeyField = $primaryKeyField;
    }

    public function getPrimaryKeyField()
    {
        return $this->primaryKeyField;
    }

    public function setPrimaryKeyValue($primaryKeyValue)
    {
        $this->primaryKeyValue = $primaryKeyValue;
    }

    public function getPrimaryKeyValue()
    {
        return $this->primaryKeyValue;
    }

    public function setExternalPosts($externalPosts) 
    {
        $this->externalPosts = $externalPosts;
    }

    public function getExternalPosts() 
    {
        return $this->externalPosts;
    }

    public function getValueCollection()
    {
        return $this->valueCollector->getCollection();
    }

    public function getValueCollector()
    {
        return $this->valueCollector;
    }

    public function setValid($valid)
    {
        $this->valid = $valid;
    }

    public function isValid()
    {
        return $this->valid;
    }

    public function setSaved($saved)
    {
        $this->saved = $saved;
    }

    public function setMessages($messages)
    {
        $this->messages = $messages;
    }

    public function addMessage($key, $message)
    {
        $this->messages[$key] = $message;
    }

    public function setRequests($requests)
    {
        $this->requests = $requests;
    }

    public function getRequests()
    {
        return $this->requests;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function getMessage($propertyAlias)
    {
        return isset($this->messages[$propertyAlias]) ? $this->messages[$propertyAlias] : null;
    }

    public function getSaved()
    {
        return $this->saved;
    }
}
