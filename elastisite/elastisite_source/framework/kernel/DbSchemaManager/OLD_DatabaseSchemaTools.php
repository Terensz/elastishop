<?php
namespace framework\packages\ToolPackage\service\DbSchemaManager;

use framework\component\parent\Service;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;

class DbSchemaManager extends Service
{
    public function updateSchema($requestedOutput = 'update')
    {
        $dbName = $this->getContainer()->getDbConnection('default')->getName();
        $packageNames = FileHandler::getAllDirNames('framework/packages');
        $notExistingTables = array();
        $schemaUpdateMap = array();
        foreach ($packageNames as $packageName) {
            $tableMapNames = FileHandler::getAllFileNames('framework/packages/'.$packageName.'/database', 'remove');
            foreach ($tableMapNames as $tableMapName) {
                if ($tableMapName && $tableMapName != '') {
                    $this->getContainer()->wireService('framework/packages/'.$packageName.'/database/'.$tableMapName.'');
                    $tableMapClass = 'framework\\packages\\'.$packageName.'\database\\'.$tableMapName;
                    $tableMap = $tableMapClass::get();
                    for ($i = 0; $i < count($tableMap); $i++) {
                        $schema = $this->getTableSchema($dbName, $tableMap[$i]['tableName']);
                        if (empty($schema)) {
                            if ($requestedOutput === 'update') {
                                $this->createTable($tableMap[$i]);
                            } else {
                                $notExistingTables[] = array(
                                    'tableName' => $tableMap[$i]['tableName'],
                                    'fieldsNum' => count($tableMap[$i]['columns'])
                                );
                            }
                        } else {
                            $diff = $this->diffBetweenMapAndSchema(
                                $tableMap[$i]['tableName'],
                                ($requestedOutput === 'update' ? 'alter' : 'getInfo'),
                                $tableMap[$i]['columns'],
                                $schema
                            );
                            $schemaUpdateMap = array_merge($schemaUpdateMap, $diff);
                        }
                    }
                }
            }
        }
        // dump($schemaUpdateMap);
        $alterSchemaQueries = array();
        if ($requestedOutput === 'update') {
            $alterSchemaQueries = $this->alterSchema($schemaUpdateMap);
            $schemaUpdateMap = array();
        }
        // exit;
        return array(
            'schemaUpdateMap' => $schemaUpdateMap,
            'notExistingTables' => $notExistingTables,
            'alterSchemaQueries' => $alterSchemaQueries
        );
    }

    public function alterSchema($schemaUpdateMap)
    {
        $stms = array();
        for ($i = 0; $i < count($schemaUpdateMap); $i++) {
            if ($schemaUpdateMap[$i]['diffType'] == 'missingColumn') {
                $stm = "ALTER TABLE ".$schemaUpdateMap[$i]['tableName']." ADD ".$schemaUpdateMap[$i]['columnName']." ".$schemaUpdateMap[$i]['alterText'];
                $stms[] = $stm;
                $this->getDbManager()->execute($stm);
            } elseif ($schemaUpdateMap[$i]['diffType'] == 'surplusColumn') {
                $stm = "ALTER TABLE ".$schemaUpdateMap[$i]['tableName']." DROP ".$schemaUpdateMap[$i]['columnName'];
                $stms[] = $stm;
                $this->getDbManager()->execute($stm);
            } elseif ($schemaUpdateMap[$i]['diffType'] == 'param') {
                $stm = "ALTER TABLE ".$schemaUpdateMap[$i]['tableName']." MODIFY ".$schemaUpdateMap[$i]['columnName']." ".$schemaUpdateMap[$i]['alterText'];
                $stms[] = $stm;
                // dump($stm);
                $this->getDbManager()->execute($stm);
                // exit;
            }
        }
        return $stms;
    }

    public function diffBetweenMapAndSchema($tableName, $action, $map, $schema)
    {
        $return = array();
        $lastColumn = null;
        foreach ($map as $columnName => $columnMap) {
            if (isset($columnMap['autoIncrement']) && $columnMap['autoIncrement'] === true) {
                if (!isset($schema[$columnName])) {

                }
            } else {
                if (!isset($schema[$columnName])) {
                    $return[] = $this->getAddColumn($tableName, $columnName, $lastColumn, $columnMap);
                } else {
                    $param = 'type';
                    if (!isset($columnMap[$param])) {
                        // Exception
                    }
                    $diff = $this->getDiff($tableName, $columnName, $param, $columnMap, $schema[$columnName]);
                    if ($diff) {
                        $return[] = $diff;
                    }
                    $param = 'length';
                    if (!isset($columnMap[$param])) {
                        $columnMap[$param] = false;
                    }
                    $diff = $this->getDiff($tableName, $columnName, $param, $columnMap, $schema[$columnName]);
                    if ($diff) {
                        $return[] = $diff;
                    }
                    $param = 'nullable';
                    if (!isset($columnMap[$param])) {
                        $columnMap[$param] = true;
                    }
                    $diff = $this->getDiff($tableName, $columnName, $param, $columnMap, $schema[$columnName]);
                    if ($diff) {
                        $return[] = $diff;
                    }
                    $param = 'autoIncrement';
                    if (!isset($columnMap[$param])) {
                        $columnMap[$param] = false;
                    } else {
                        $diff = $this->getDiff($tableName, $columnName, $param, $columnMap, $schema[$columnName]);
                        if ($diff) {
                            $return[] = $diff;
                        }
                    }
                    $param = 'default';
                    if (!isset($columnMap[$param])) {
                        $columnMap[$param] = null;
                    }
                    $diff = $this->getDiff($tableName, $columnName, $param, $columnMap, $schema[$columnName]);
                    if ($diff) {
                        $return[] = $diff;
                    }
                }
            }
            $lastColumn = $columnName;
        }

        $schemaKeys = array_keys($schema);
        $mapKeys = array_keys($map);
        foreach ($schemaKeys as $schemaColumn) {
            if (!in_array($schemaColumn, $mapKeys)) {
                $return[] = array(
                    'tableName' => $tableName,
                    'columnName' => $schemaColumn,
                    'diffType' => 'surplusColumn',
                    'param' => null,
                    'mapValue' => null,
                    'schemaValue' => null,
                    'alterText' => null,
                    'newColumn' => null
                );
            }
        }
        return $return;
    }

    public function mendColumnMap($columnMap)
    {
        $defaultIsDefined = isset($columnMap['default']);
        $columnMap['default'] = $defaultIsDefined ? $columnMap['default'] : false;
        if (gettype($columnMap['default']) == 'string' && $columnMap['default'] === '0') {
            $columnMap['default'] = '0';
        }
        $columnMap['type'] = isset($columnMap['type']) ? strtoupper($columnMap['type']) : null;
        $columnMap['default'] = isset($columnMap['default']) ? strtoupper($columnMap['default']) : null;
        $columnMap['nullable'] = isset($columnMap['nullable']) ? strtoupper($columnMap['nullable']) : true;
        if ($columnMap['type'] == 'BOOLEAN') {
            $columnMap['type'] = 'TINYINT';
        }
        if ($columnMap['default'] == 'true') {
            $columnMap['default'] = '1';
        }
        if (strtolower($columnMap['default']) == 'now()') {
            $columnMap['default'] = 'now()';
        }
        if ($columnMap['default'] === null) {
            $columnMap['default'] = 'null';
        }
        if ($columnMap['type'] == 'TEXT') {
            $columnMap['length'] = '65535';
        }
        if ($columnMap['type'] == 'INT') {
            $columnMap['length'] = null;
        }
        if ($columnMap['nullable'] && !$columnMap['default']) {
            $columnMap['default'] = 'null';
        }
        if ($columnMap['default'] && !in_array($columnMap['default'], array('null', 'now()'))) {
            $columnMap['default'] = "'".$columnMap['default']."'";
        }
        return $columnMap;
    }

    public function getAddColumn($tableName, $columnName, $after, $columnMap)
    {
        $columnMap = $this->mendColumnMap($columnMap);

        $colDefinitionStr = '';
        $nullStr = '';
        $defaultStr = $columnMap['default'] ? " DEFAULT ".$columnMap['default'] : "";
        if (in_array($columnMap['type'], array('TINYINT', 'DATETIME', 'TEXT', 'INT'))) {
            $colDefinitionStr = $columnMap['type'];
        } else {
            $colDefinitionStr = $columnMap['type'].'('.$columnMap['length'].')';
        }
        if ($columnMap['nullable'] == true) {
            $nullStr = '';
        } else {
            $nullStr = ' NOT NULL';
        }
        $autoIncrementStr = isset($columnMap['autoIncrement']) && $columnMap['autoIncrement'] === true ? ' auto_increment' : '';

        return array(
            'tableName' => $tableName,
            'columnName' => $columnName,
            'diffType' => 'missingColumn',
            'param' => null,
            'mapValue' => null,
            'schemaValue' => null,
            'alterText' => $colDefinitionStr.$nullStr.$autoIncrementStr.$defaultStr.($after ? ' AFTER '.$after : ''),
            'newColumn' => array(
                'type' => $columnMap['type'],
                'length' => $columnMap['length'],
                'nullable' => $columnMap['nullable'],
                'default' => $columnMap['default']
            )
        );
    }

    public function getDiff($tableName, $columnName, $param, $columnMap, $columnSchema)
    {
        $columnMap = $this->mendColumnMap($columnMap);
        if (!in_array(strtoupper($columnMap['default']), array(false, 'NULL', 'NOW()'))) {
            $columnSchema['default'] = "'".trim(strtoupper($columnSchema['default']), "'")."'";
        }

        $colDefinitionStr = '';
        $nullStr = '';
        $defaultStr = $columnMap['default'] ? " DEFAULT ".$columnMap['default'] : "";
        if (in_array($columnMap['type'], array('TINYINT', 'DATETIME', 'TEXT', 'INT'))) {
            $colDefinitionStr = $columnMap['type'];
        } else {
            $colDefinitionStr = $columnMap['type'].'('.$columnMap['length'].')';
        }
        if ($columnMap['nullable'] == true) {
            $nullStr = '';
        } else {
            $columnSchema['default'] = $columnSchema['default'] == 'null' ? null : $columnSchema['default'];
            $nullStr = ' NOT NULL';
        }
        $autoIncrementStr = isset($columnMap['autoIncrement']) && $columnMap['autoIncrement'] === true ? ' auto_increment' : '';
        // dump($columnMap);
        $diff = null;
        if (strtoupper($columnMap[$param]) != strtoupper($columnSchema[$param])) {
            $diff = array(
                'tableName' => $tableName,
                'columnName' => $columnName,
                'diffType' => 'param',
                'param' => $param,
                'mapValue' => $columnMap[$param],
                'schemaValue' => $columnSchema[$param],
                'alterText' => $colDefinitionStr.$nullStr.$autoIncrementStr.$defaultStr,
                'newColumn' => null
            );
        }
        return $diff;
    }

    public function getTableSchema($dbName, $tableName)
    {
        $stm = "SELECT
                COLUMN_NAME as name
                , DATA_TYPE as datatype
                , CHARACTER_MAXIMUM_LENGTH as length
                , COLUMN_DEFAULT as 'default'
                , IS_NULLABLE as nullable
                , EXTRA as extra
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = '".$dbName."' AND TABLE_NAME = '".$tableName."'
                ORDER BY ORDINAL_POSITION ASC";
        $rawSchema = $this->getDbManager()->findAll($stm);
        // dump($stm);exit;
        $return = array();
        foreach ($rawSchema as $rawSchemaColumnParams) {
            $return[$rawSchemaColumnParams['name']] = array(
                'type' => strtoupper($rawSchemaColumnParams['datatype']),
                'autoIncrement' => $rawSchemaColumnParams['extra'] == 'auto_increment' ? true : false,
                'length' => $rawSchemaColumnParams['length'],
                'nullable' => $rawSchemaColumnParams['nullable'] == 'NO' ? false : true,
                'default' => ($rawSchemaColumnParams['default'] == 'CURRENT_TIMESTAMP' ? 'now()'
                    : ($rawSchemaColumnParams['default'] === null ? 'null' : $rawSchemaColumnParams['default']))
            );
        }
        return $return;
    }

    public function createTable($tableMapElement)
    {
        $stm = $this->makeCreateTableStatement($tableMapElement);
        $rawSchema = $this->getDbManager()->execute($stm);
    }

    public function makeCreateTableStatement($tableMapElement)
    {
        $return = "CREATE TABLE `".$tableMapElement['tableName']."` (
        ";

        $columnList = '';

        foreach ($tableMapElement['columns'] as $columnName => $columnMap) {
            $columnMap = $this->mendColumnMap($columnMap);
            // dump($columnMap);
            $colDefinitionStr = '';
            $nullStr = '';
            $defaultStr = $columnMap['default'] ? " DEFAULT ".strtoupper($columnMap['default']) : "";
            $defaultStr = str_replace("'NULL'", "NULL", $defaultStr);
            $defaultStr = str_replace("'NOW()'", "NOW()", $defaultStr);
            // dump($defaultStr);
            if (in_array($columnMap['type'], array('TINYINT', 'DATETIME', 'TEXT', 'INT'))) {
                $colDefinitionStr = $columnMap['type'];
            } else {
                $colDefinitionStr = $columnMap['type'].'('.$columnMap['length'].')';
            }
            if ($columnMap['nullable'] == true) {
                $nullStr = '';
            } else {
                $nullStr = ' NOT NULL';
            }
            $autoIncrementStr = isset($columnMap['autoIncrement']) ? ' auto_increment' : '';
            $columnList .= "`".$columnName."` ".$colDefinitionStr.$nullStr.$autoIncrementStr.$defaultStr.",
            ";
        }
        $return .= "
            ".$columnList."
            PRIMARY KEY (".(isset($tableMapElement['primaryKey']) ? $this->makePrimaryKeyString($tableMapElement['primaryKey']) : '`id`').")
        )
        COLLATE='".(isset($tableMapElement['collate']) ? $tableMapElement['collate'] : 'utf8_hungarian_ci')."'
        ENGINE=".(isset($tableMapElement['engine']) ? $tableMapElement['engine'] : 'InnoDB')."
        AUTO_INCREMENT=".(isset($tableMapElement['autoIncrement']) && $tableMapElement['autoIncrement'] === true ? $tableMapElement['autoIncrement'] : '1')."
        ";
// exit;
        return $return;
    }

    // public function makeDataTypeString($columnMap)
    // {
    //     $defaultStr = '';
    //     $nullStr = '';
    //     if (isset($columnMap['default'])) {
    //         $defaultStr = " DEFAULT ".$columnMap['default'];
    //     }
    //
    //     if (isset($columnMap['nullable']) && $columnMap['nullable'] === true) {
    //         $defaultStr = " DEFAULT NULL";
    //     } else {
    //         $nullStr = " NOT NULL";
    //         $defaultStr = isset($columnMap['default']) ? ' DEFAULT '.$columnMap['default'] : '';
    //     }
    //
    //     if (isset($columnMap['autoIncrement'])) {
    //         // $nullStr = ' alma';
    //         $nullStr = " NOT NULL";
    //         $defaultStr = " AUTO_INCREMENT";
    //     }
    //     return $nullStr.$defaultStr;
    // }

    public function makePrimaryKeyString($array)
    {
        $return = '';
        foreach ($array as $element) {
            $return .= ($return != '' ? ', ' : '').'`'.$element.'`';
        }
        return $return;
    }
}
