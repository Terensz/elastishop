<?php
namespace framework\component\parent;

// use framework\component\parent\Repository;
use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\kernel\base\Reflector;
use framework\component\exception\ElastiException;

class TechnicalRepository extends Repository
{
    protected $entity;
    protected $connectionId = 'default';

    public function __construct()
    {

    }

    public function getRepositoryData()
    {
        return null;
    }

    public function isActive() : bool
    {
        return $this->createNewEntity()->isActive();
    }

    public function isDeletable($id)
    {
        return true;
    }

    public function cleanUpOrphans()
    {
        return false;
    }

    public function getRepositoryType()
    {
        return self::REPOSITORY_TYPE_TECHNICAL; 
        // 'technical';
    }

    public function getEntity()
    {
        if (!$this->entity) {
            $this->entity = $this->createNewEntity();
        }
        return $this->entity;
    }

    public function getPrimaryKeyField()
    {
        return 'id';
    }

    public function find($id)
    {
        return null;
    }

    public function findBy($filter = null, $limit = false, $queryType = 'result')
    {
        return [];
    }

    public function findOneBy($filter = null, $limit = false, $queryType = 'result')
    {
        return null;
    }

    public function getEntityName()
    {
        $repoClass = get_class($this);
        return str_replace('Repository', 'Entity', $repoClass);
    }

    public function createNewEntity()
    {
        $repoClass = get_class($this);
        $entityClass = str_replace('\\repository\\', '\\entity\\', $repoClass);
        $entityClass = str_replace('Repository', '', $entityClass);
        $entityPath = str_replace('\\', '/', $entityClass);
        $this->getContainer()->setService($entityPath);

        return new $entityClass();
    }

    public function collectRecordData($filter, $queryType = 'result', $forceCollection = false, $debug = false)
    {
        return array();
    }

    public function createEmptyRecordData()
    {
        return array();
    }

    public function makeEntityFromRecordData($recordData, $entity = null) : Entity
    {
        return new Entity();
    }

    public function getTableName()
    {
        return false;
    }


    public function store($entity)
    {
        return false;
    }

    // protected function update(object $entity)
    // {
    //     $setStr = '';
    //     $counter = 0;
    //     $reflector = new Reflector();
    //     $propertiesArray = $reflector->getProperties($entity);
    //     $tableFields = $this->getTableFields();
    //     foreach ($propertiesArray as $reflectionProperty) {
    //         $property = $reflectionProperty->getName();
    //         $getter = 'get'.ucfirst($property);
    //         $value = $entity->$getter();
    //         if (!is_object($value)) {
    //             $fieldName = BasicUtils::camelToSnakeCase($property);
    //             $setStr .= ($counter == 0 ? ' ' : ' , ').$fieldName.' = :'.$fieldName;
    //             $params[$fieldName] = $value;
    //             $counter++;
    //         } else {
    //             $fieldNameSupposition = BasicUtils::camelToSnakeCase($property).'_id';
    //             foreach ($tableFields as $tableField) {
    //                 if ($tableField['Field'] == $fieldNameSupposition) {
    //                     $setStr .= ($counter == 0 ? ' ' : ' , ').$tableField['Field'].' = :'.$tableField['Field'];
    //                     $params[$tableField['Field']] = $value->getId();
    //                     $counter++;
    //                 }
    //             }
    //         }
    //     }

    //     $stm = "UPDATE ".$this->getTableName()." SET ".$setStr." WHERE id = :id ";
    //     $params['id'] = $entity->getId();
    //     $dbm = $this->getDbManager();
    //     $dbm->execute($stm, $params);
    // }
}
