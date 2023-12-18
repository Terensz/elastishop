<?php
namespace framework\component\parent;

use App;
use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\component\parent\Entity;

abstract class Repository extends Kernel
{
    const REPOSITORY_TYPE_TECHNICAL = 'technical';
    
    abstract protected function getRepositoryType();
    
    abstract protected function findBy($filter = null, $queryType = 'result');

    abstract protected function findOneBy($filter = array());

    public function find($id)
    {
        // dump($id);
        if (!$id) {
            return false;
        }
        $return = $this->findOneBy(['conditions' => [['key' => 'id', 'value' => $id]]]);
        // dump($return);exit;
        return $return;
    }

    public function findOnWebsite($id)
    {
        // dump($id);
        if (!$id) {
            return false;
        }
        $return = $this->findOneBy(['conditions' => [
            ['key' => 'id', 'value' => $id],
            ['key' => 'website', 'value' => App::getWebsite()],
        ]]);
        // dump($return);
        return $return;
    }

    public function transformFilter($filter)
    {
        // if (!$filter || isset($filter['conditions']) || isset($filter['maxResults']) || isset($filter['orderBy'])) {
        //     return $filter;
        // }
        // dump($filter);exit;
        // $filter = array();
        if (!isset($filter)) {
            $filter = array();
        }

        // if (!is_array($filter)) {
        //     $filter = array();
        // }

        if (!isset($filter['conditions']) && is_array($filter) ) {
            $conditions = array();
            foreach ($filter as $key => $value) {
                $conditions[] = array(
                    'key' => $key,
                    'value' => $value
                );
            }
            $filter['conditions'] = $conditions;
        }
        
        if (!isset($filter['orderBy'])) {
            // $filter['orderBy'] = ['field' => 'id', 'direction' => 'DESC'];
            $filter['orderBy'] = null;
        }
        $filter['orderByStr'] = "";
        if ($filter['orderBy'] && is_array($filter['orderBy'])) {
            $filter['orderByStr'] = " ORDER BY ";
            $counter = 0;
            foreach ($filter['orderBy'] as $orderByLoop) {
                $filter['orderByStr'] .= ($counter == 0 ? "" : ", ").$orderByLoop['field']." ".$orderByLoop['direction'];
                $counter++;
            }
        }
        if (!isset($filter['maxResults'])) {
            $filter['maxResults'] = null;
        }
        if (!isset($filter['currentPage'])) {
            $filter['currentPage'] = 1;
        }
        $filter['limitStr'] = "";
        if ($filter['maxResults']) {
            $pageFirstIndex = (($filter['currentPage'] - 1) * $filter['maxResults']);
            $filter['limitStr'] = " LIMIT ".$pageFirstIndex.", ".$filter['maxResults']." ";
        }
        // dump($filter);
        // dump('-------');

        return $filter;
    }

    public function filterHasCondition($filter, $searchedCondition)
    {
        $filter = $this->transformFilter($filter);
        foreach ($filter['conditions'] as $condition) {
            if ($condition['key'] == $searchedCondition) {
                return true;
            }
        }




        return false;
    }

    public function getFilterConditionValue($filter, $searchedCondition)
    {
        $filter = $this->transformFilter($filter);
        foreach ($filter['conditions'] as $condition) {
            if ($condition['key'] == $searchedCondition) {
                return $condition['value'];
            }
        }
        return false;
    }

    public function getRepositoryData()
    {
        $repoClass = get_class($this);
        $repoClassName = BasicUtils::explodeAndGetElement($repoClass, '\\', 'last');
        $entityClassName = str_replace('Repository', '', $repoClassName);
        $entityClass = str_replace($repoClassName, $entityClassName, $repoClass);
        $entityClass = str_replace('\\repository\\', '\\entity\\', $entityClass);
        return array(
            'repoClass' => $repoClass,
            'repoClassName' => $repoClassName,
            'entityClass' => $entityClass,
            'entityClassName' => $entityClassName,
            'entityPath' => str_replace('\\', '/', $entityClass)
        );
    }

    abstract public function collectRecordData($filter, $queryType = 'result', $forceCollection = false, $debug = false);

    abstract public function createEmptyRecordData();

    abstract public function createNewEntity();

    abstract public function makeEntityFromRecordData($recordData, $entity = null);

    abstract public function getPrimaryKeyField();

    abstract public function getEntityName();

    abstract public function getTableName();

    abstract public function isActive() : bool;

    abstract public function store($entity);
}
