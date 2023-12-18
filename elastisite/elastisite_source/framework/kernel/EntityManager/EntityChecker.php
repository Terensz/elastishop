<?php
namespace framework\kernel\EntityManager;

use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\component\exception\ElastiException;

class EntityChecker extends Kernel
{
    private $tableFields = [];
    private $propsAsTableFields = [];

    public function createEntityMap()
    {
        // if ($this->getContainer()->getEntityMap()) {
        //     return $this->getContainer()->getEntityMap();
        // }
        $entityMap = $this->getContainer()->searchFileMap(['classType' => 'entity']);
		$tableNames = $this->getDbManager()->getTableNames();
		$result = array();
        // dump($entityMap);//exit;
        foreach ($entityMap as $entityMapElement) {
            // dump($entityMapElement);
            $condition = null;
            $parentClassName = null;
            $createTableStatement = null;
            $onCreateQueries = null;
            $entityTableNameFormat = BasicUtils::camelToSnakeCase(lcfirst($entityMapElement['className']));
            $entityAttributes = null;
            $databaseTableErrors = null;
			// dump($searchedTableName);
			// dump($entityMapElement);
            $pathToFile = $entityMapElement['path'].'/'.$entityMapElement['fileName'];
            $this->getContainer()->wireService($pathToFile);
            $entityClass = $entityMapElement['namespace'];
            // dump($entityClass);
            if (class_exists($entityClass)) {
                $entity = new $entityClass();
                $parentClassName = BasicUtils::explodeAndGetElement(get_parent_class($entity), '\\', 'last');
                if ($parentClassName == 'DbEntity') {
                    // dump($entity->getEntityAttributes(false));

                    $debug = false;
                    if ($entityTableNameFormat == 'product') {
                        // dump($entity);
                        // dump($entityAttributes);
                        // dump($databaseTableErrors);
                        $debug = true;
                    }

                    $entityAttributes = $entity->getEntityAttributes(true, $debug);

                    if ($entityTableNameFormat == 'product') {
                        // dump($entity);
                        // dump($entityAttributes);
                        // dump($databaseTableErrors);
                        // $debug = true;
                    }

                    $condition = 'inactive';
                    if ($entityAttributes['active']) {
                        $condition = in_array($entityTableNameFormat, (!$tableNames ? [] : $tableNames)) ? 'table.exists' : 'missing.table';
                        // $result[$entityMapElement['className']] = array(
                        //     'entityName' => $entityMapElement['className'],
                        //     'parentClassName' => $parentClassName,
                        //     'condition' => $condition
                        //     // '' => 
                        // );
                        // dump($entity);
                    }

                    if ($condition == 'table.exists') {
                        // dump($entityTableNameFormat);
                        // dump($entityAttributes);

                        $databaseTableErrors = $this->getTableErrors($entityTableNameFormat, $entityAttributes, $debug);
                        // dump($tableErrors);
                    }

                    if (defined(get_class($entity).'::CREATE_TABLE_STATEMENT')) {
                        // dump($entity::CREATE_TABLE_STATEMENT);
                        $createTableStatement = $entity::CREATE_TABLE_STATEMENT;
                    }
                    if (defined(get_class($entity).'::ONCREATE_QUERIES')) {
                        // dump($entity::ONCREATE_QUERIES);
                        $onCreateQueries = $entity::ONCREATE_QUERIES;
                    }

                    // else {
                    //     $result[$entityMapElement['className']] = array(
                    //         'entityName' => $entityMapElement['className'],
                    //         'parentClassName' => $parentClassName,
                    //         'condition' => 'inactive'
                    //         // '' => 
                    //     );
                    // }
                    // dump($entity);
                    // dump($entity);
                    // dump($entity->getEntityAttributes('active')); 
                }
            } else {
                // $result[$entityMapElement['className']] = array(
                //     'entityName' => $entityClass,
                //     // 'entityName' => BasicUtils::explodeAndRemoveElement(BasicUtils::explodeAndRemoveElement($entityClass, '\\', 'first'), '\\', 'first'),
                //     'parentClassName' => '',
                //     'condition' => 'missing.class'
                //     // '' => 
                // );
            }
            $result[$entityMapElement['className']] = array(
                'entityName' => $entityMapElement['className'],
                'parentClassName' => $parentClassName,
                'condition' => $condition,
                'createTableStatement' => $createTableStatement,
                'onCreateQueries' => $onCreateQueries,
                'databaseTableErrors' => $databaseTableErrors
                // 'entityAttributes' => $entityAttributes
                // '' => 
            );
		}
		return $result;
    }

    public function getTableErrors($tableName, $entityAttributes, $debug)
    {
        // $tableProperties = $this->getTableProperties($tableName);
        // dump($entityAttributes);
        $return = [
            'missingFields' => $this->getMissingFields($tableName, $entityAttributes, $debug),
            'unnecessaryFields' => $this->getUnnecessaryFields($tableName, $entityAttributes, $debug)
        ];
        if ($return['missingFields'] == array() && $return['unnecessaryFields'] == array()) {
            return null;
        }
        return $return;
    }

    public function getTableProperties($tableName)
    {
        // return $this->getDbManager()->getFieldAttributes($tableName);

        if (isset($this->tableFields[$tableName])) {
            return $this->tableFields[$tableName];
        }
        $fields = [];

        // $stm = "DESCRIBE ".$tableName;
        // $rawFields = $this->getDbManager()->findAll($stm);

        $rawFields = $this->getDbManager()->getFieldAttributes($tableName);
        
        foreach ($rawFields as $rawField) {
            $fields[] = $rawField['Field'];
        }
        $this->tableFields[$tableName] = $fields;


        return $this->tableFields[$tableName];
    }

    public function getMissingFields($tableName, $entityAttributes, $debug = false)
    {
        $missingFields = array();
        $tableProperties = $this->getTableProperties($tableName);
        // if ($tableName == 'referer') {
        //     dump($entityAttributes['propertyMap']);
        // }
        
        foreach ($entityAttributes['propertyMap'] as $property => $params) {
            // dump($params);
            // dump($entityAttributes['relations']);
            $fieldName = BasicUtils::camelToSnakeCase($params['singularPropertyName']);
            $markFieldAsMissing = false;
            if ($params['isObject']) {
                //dump($entityAttributes['relations'][ucfirst($params['singularPropertyName'])]);
                if (!isset($entityAttributes['relations'][ucfirst($params['singularPropertyName'])])) {
                    dump($entityAttributes);
                }
                $refContainer = $entityAttributes['relations'][ucfirst($params['singularPropertyName'])]['referenceContainerTable'];
                if (in_array($refContainer, array('this', 'selfReferenced'))) {
                    $fieldName = $fieldName.'_id';
                    $this->propsAsTableFields[$tableName][] = $fieldName;
                    if (!in_array($fieldName, $tableProperties)) {
                        // var_dump($entityAttributes);
                        // $missingFields[] = $fieldName;
                        $markFieldAsMissing = true;
                    }
                }
            } else {
                $this->propsAsTableFields[$tableName][] = $fieldName;

                if (!in_array($fieldName, $tableProperties) && !$params['technical']) {
                // if (!in_array($fieldName, $tableProperties)) {
                    // dump($fieldName);
                    // dump($params);
                    // var_dump($entityAttributes);
                    // $missingFields[] = $fieldName;
                    $markFieldAsMissing = true;
                }
            }

            // if ($tableName == 'asc_sample_entry') {
            //     dump($fieldName);
            //     dump($markFieldAsMissing);
            //     dump($params);
            // }
            if ($markFieldAsMissing) {
                if (isset($entityAttributes['passOverMissingFields']) && in_array($fieldName, $entityAttributes['passOverMissingFields'])) {

                } else {
                    $missingFields[] = $fieldName;
                }
            }
            // dump($fieldName);
        }
        return $missingFields;
    }

    public function getUnnecessaryFields($tableName, $entityAttributes, $debug = false)
    {
        // if ($tableName == 'asc_entry_panel_group') {
        //     dump($this->getTableProperties($tableName));
        // }

        $unnecessaryFields = array();

        // if ($tableName == 'asc_sample_entry') {
        //     dump($this->getTableProperties($tableName));
        //     dump($entityAttributes);
        // }

        // dump($this->propsAsTableFields[$tableName]);//exit;
        foreach ($this->getTableProperties($tableName) as $tableProperty) {
            // $props = array_keys($entityAttributes['propertyMap']);
            // dump($this->propsAsTableFields[$tableName]);
            if (!in_array($tableProperty, $this->propsAsTableFields[$tableName])) {
                // dump($tableName);
                // dump($this->propsAsTableFields[$tableName]);
                if (isset($entityAttributes['passOverUnnecessaryFields']) && in_array($tableProperty, $entityAttributes['passOverUnnecessaryFields'])) {

                } else {
                    $unnecessaryFields[] = $tableProperty;
                }
            }
            // dump($props);
        }
        return $unnecessaryFields;
    }

    public function transformProperties($props)
    {

    }

    // public function getDbEntitiesCondition()
    // {

    // }

    // public function getDbEntitiesCondition()
    // {
    //     $entityMap = $this->getContainer()->searchFileMap(['classType' => 'entity']);
	// 	$tableNames = $this->getDbManager()->getTableNames();
	// 	$result = array();
    //     // dump($tableNames);exit;
    //     foreach ($entityMap as $entityMapElement) {
    //         $entityTableNameFormat = BasicUtils::camelToSnakeCase(lcfirst($entityMapElement['className']));
	// 		// dump($searchedTableName);
	// 		// dump($entityMapElement);
    //         $pathToFile = $entityMapElement['path'].'/'.$entityMapElement['fileName'];
    //         $this->getContainer()->wireService($pathToFile);
    //         $entityClass = $entityMapElement['namespace'];
    //         if (class_exists($entityClass)) {
    //             $entity = new $entityClass();
    //             $parentClassName = BasicUtils::explodeAndGetElement(get_parent_class($entity), '\\', 'last');
    //             if ($parentClassName == 'DbEntity') {
    //                 // dump($entity);
    //                 // dump($entity->getEntityAttributes());
    //                 if ($entity->getEntityAttributes()['active']) {
    //                     $result[$entityMapElement['className']] = array(
    //                         'entityName' => $entityMapElement['className'],
    //                         'parentClassName' => $parentClassName,
    //                         'condition' => (in_array($entityTableNameFormat, $tableNames) ? 'good' : 'missing.table')
    //                         // '' => 
    //                     );
    //                 } else {
    //                     $result[$entityMapElement['className']] = array(
    //                         'entityName' => $entityMapElement['className'],
    //                         'parentClassName' => $parentClassName,
    //                         'condition' => 'inactive'
    //                         // '' => 
    //                     );
    //                 }
    //                 // dump($entity);
    //                 // dump($entity->getEntityAttributes('active')); 
    //             }
    //         } else {
    //             $result[$entityMapElement['className']] = array(
    //                 'entityName' => $entityClass,
    //                 // 'entityName' => BasicUtils::explodeAndRemoveElement(BasicUtils::explodeAndRemoveElement($entityClass, '\\', 'first'), '\\', 'first'),
    //                 'parentClassName' => '',
    //                 'condition' => 'missing.class'
    //                 // '' => 
    //             );
    //         }
	// 	}
	// 	// dump($result);
	// 	return $result;
    // }
}
