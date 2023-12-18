<?php
namespace framework\packages\DataGridPackage\service;

use App;
use framework\component\parent\Service;
use framework\kernel\utility\BasicUtils;
use framework\packages\DataGridPackage\service\DataGrid;

class GridData extends Service
{
    private $collection = [];

    /**
     * @var DataGrid
    */
    private $dataGrid;

    private $filteredIds = [];

    /**
     * @var array Serial array of column names
    */
    // private $columnNames = [];

    private $conditionPosts;

    private $orderPosts;

    private $multiselectValues = [];

    // private $conditionPosts;

    private $columnParams = [];

    private $dateColumns = [];

    // private $dateColumns;

    private $idColumnName;

    private $statusColumn = false;

    private $filteredQueryClassSource;

    private $queryClassSource;

    /**
     * @var array Data array
    */
    private $dataArray;

    private $usedFieldNames = [];

    /**
     * @var object The main entity-repository of the grid-data
    */
    private $primaryRepository;

    private $filter;

    // private $totalRowsCount;

    // private $startingOffset = 0;

    private $totalRowsCount;

    private $query;

    // private $orderByColumnName;

    // private $orderByDirection;

    // public function setColumnNames(array $columnNames)
    // {
    //     $this->columnNames = $columnNames;
    // }

    public function getUsedFieldNames() : array
    {
        return $this->usedFieldNames;
    }

    public function getFilteredIds() : array
    {
        return $this->filteredIds;
    }

    // public function getColumnNames() : array
    // {
    //     return $this->columnNames;
    // }

    public function getColumnParams() : array
    {
        return $this->columnParams;
    }

    public function setDataArray(array $dataArray)
    {
        $this->dataArray = $dataArray;
    }

    public function getDataArray() : array
    {
        return $this->dataArray;
    }

    public function getDateColumns() : array
    {
        return $this->dateColumns;
    }

    public function setPrimaryRepository($primaryRepository)
    {
        $this->primaryRepository = $primaryRepository;
        // $this->detectQueriesClassSource();
        // $this->createColumnList();
    }

    // public function setDateProps()
    // {
    //     //dump($this->columnParams);
    //     foreach ($this->columnParams as $column) {
    //         if ($column['dataType'] == 'date') {
    //             $this->dateColumns[] = $column['name'];
    //         }
    //     }
    // }

    public function setPostKeys()
    {
        foreach ($this->columnParams as $column) {
            $property = BasicUtils::snakeToCamelCase($column['name']);
            // if ($column['dataType'] == 'date') {
            //     $this->dateColumns[] = $property;
            //     $this->conditionPosts[$property.'Start'] = [
            //         'type' => 'dateStart',
            //         'columnName' => $column['name'],
            //         'propertyName' => $property,
            //         'value' => null
            //     ];
            //     $this->conditionPosts[$property.'End'] = [
            //         'type' => 'dateEnd',
            //         'columnName' => $column['name'],
            //         'propertyName' => $property,
            //         'value' => null
            //     ];
            // } else {
            //     $this->conditionPosts[$property] = [
            //         'type' => $this->dataGrid->getPropertyInputType($property),
            //         'columnName' => $column['name'],
            //         'propertyName' => $property,
            //         'value' => null
            //     ];
            // }
            $type = $this->dataGrid->getPropertyInputType($property);
            if ($column['dataType'] == 'date') {
                $this->dateColumns[] = $property;
                $type = 'date';
            }
            $this->conditionPosts[$property] = [
                'type' => $type,
                'columnName' => $column['name'],
                'propertyName' => $property,
                'value' => null
            ];
        }

        if ($this->getContainer()->getRequest()->getAll()) {
            foreach ($this->getContainer()->getRequest()->getAll() as $postName => $value) {
                $prefix = $this->dataGrid->getDataGridId().'_';
                if (substr($postName, 0, strlen($prefix)) == $prefix) {
                    $key = substr($postName, strlen($prefix));
                    $property = BasicUtils::snakeToCamelCase($key);
                    $this->conditionPosts[$property]['value'] = $value;
                }
            }
        }
    }

    public function setConditionPostValue($requestKey, $value)
    {
        $this->conditionPosts[$requestKey]['value'] = $value;
    }

    public function getConditionPosts()
    {
        return $this->conditionPosts;
    }

    // public function getFilterPosts()
    // {
    //     return $this->filterPosts;
    // }

    public function createColumnList()
    {
        $rawColumnParams = $this->primaryRepository->getGridColumnParams();
        // dump($rawColumnParams);
        $arrangedRawColumnParams = [];

        foreach ($rawColumnParams as $rawColumnParamsIndex => $rawColumnParamsRow) {
            if ($rawColumnParamsRow['name'] == 'deletable') {
                $rawColumnParams[$rawColumnParamsIndex]['type'] = 'technical';
            }
        }

        // dump($rawColumnParams);exit;

        if ($this->filteredQueryClassSource == 'parent') {
            foreach ($rawColumnParams as $rawColumnParamsLoop) {
                $colNameLastPart = BasicUtils::explodeAndGetElement($rawColumnParamsLoop['name'], '_', 'last');
                if (in_array($rawColumnParamsLoop['type'], array('string', 'numeric', 'date')) 
                    && count($arrangedRawColumnParams) <= 12 && !($colNameLastPart == 'id' 
                    && $rawColumnParamsLoop['type'] == 'numeric' && $rawColumnParamsLoop['name'] != 'id')
                    ) {
                    $arrangedRawColumnParams[] = $rawColumnParamsLoop;
                }
            }
        } else {
            $arrangedRawColumnParams = $rawColumnParams;
        }

        $arrangedUsedRawColumnParams = [];
        foreach ($arrangedRawColumnParams as $arrangedRawColumnParamsLoop) {
            if (in_array($arrangedRawColumnParamsLoop['name'], $this->usedFieldNames)) {
                $arrangedUsedRawColumnParams[] = $arrangedRawColumnParamsLoop;
            }
        }

        $arrangedRawColumnParams = $arrangedUsedRawColumnParams;

        // dump($arrangedRawColumnParams);
        // $filteredColumnParams = [];
        foreach ($arrangedRawColumnParams as $index => $arrangedRawColumnParamsLoop) {
            $arrangedRawColumnParams[$index]['propertyName'] = BasicUtils::snakeToCamelCase($arrangedRawColumnParamsLoop['name']);
            if ($arrangedRawColumnParamsLoop['name'] == 'status') {
                $arrangedRawColumnParams[$index]['length'] = 100;
            }

            if ($arrangedRawColumnParamsLoop['type'] == 'technical') {
                $arrangedRawColumnParams[$index]['visible'] = false;
            } else {
                if ($arrangedRawColumnParamsLoop['name'] == 'id') {
                    $this->idColumnName = 'id';
                    $arrangedRawColumnParams[$index]['visible'] = false;
                } else {
                    $arrangedRawColumnParams[$index]['visible'] = true;
                }
            }
        }

        // dump($arrangedRawColumnParams);

        if ($this->dataGrid->getDeleteDisabled() == false) {
            $arrangedRawColumnParams[] = [
                'role' => 'deleteButton',
                'name' => 'deleteButton',
                'type' => 'deleteButton',
                'length' => 40,
                'propertyName' => null,
                'title' => trans('delete'),
                'dataType' => null,
                'visible' => true
            ];
        }

        // dump($arrangedRawColumnParams);exit;

        $columnParams = [];
        $distributedColumnParams = $this->distributeWidthUnits($arrangedRawColumnParams);
        foreach ($distributedColumnParams as $distributedColumnParamsRow) {
            $columnParams[$distributedColumnParamsRow['propertyName']] = [
                'role' => isset($distributedColumnParamsRow['role']) ? $distributedColumnParamsRow['role'] : 'data',
                'name' => $distributedColumnParamsRow['name'],
                'propertyName' => $distributedColumnParamsRow['propertyName'],
                'title' => isset($distributedColumnParamsRow['title']) ? $distributedColumnParamsRow['title'] : trans(str_replace('_', '.', $distributedColumnParamsRow['name'])),
                'dataType' => $distributedColumnParamsRow['type'],
                'visible' => $distributedColumnParamsRow['visible'],
                'widthUnits' => $distributedColumnParamsRow['widthUnits']
            ];
        }

        $this->columnParams = $columnParams;
    }

    public function distributeWidthUnits($filteredColumnParams)
    {
        $widthUnits = 12;
        //dump($filteredColumnParams);
        for ($i = 0; $i < 2; $i++) {
            foreach ($filteredColumnParams as $index => $filteredColumnParamsRow) {
                if (!isset($filteredColumnParams[$index]['widthUnits'])) {
                    $filteredColumnParams[$index]['widthUnits'] = 0;
                }
                if ($filteredColumnParamsRow['visible'] && $widthUnits > 0) {
                    if ($i == 0 || ($i == 1 && !in_array($filteredColumnParamsRow['propertyName'], $this->dataGrid->getDefaultNarrowCols()))) {
                        $widthUnits--;
                        $filteredColumnParams[$index]['widthUnits']++;
                    }
                }
            }
        }
        return $this->furtherDistributeWidthUnits($filteredColumnParams, $widthUnits, true);
    }

    public function furtherDistributeWidthUnits($filteredColumnParams, $widthUnits, $onlyLongCols)
    {
        if ($widthUnits <= 0) {
            return $filteredColumnParams;
        }
        foreach ($filteredColumnParams as $index => $filteredColumnParamsRow) {
            if ($widthUnits > 0) {
                if ($filteredColumnParamsRow['visible'] && (($onlyLongCols && $filteredColumnParamsRow['length'] > 70) || (!$onlyLongCols))) {
                    $widthUnits--;
                    $filteredColumnParams[$index]['widthUnits']++;
                }
            }
        }
        if ($widthUnits == 0) {
            return $filteredColumnParams;
        } else {
            return $this->furtherDistributeWidthUnits($filteredColumnParams, $widthUnits, ($onlyLongCols ? false : true));
        }
    }

    public function getPrimaryRepository()
    {
        return $this->primaryRepository;
    }

    public function setFilter($filter = null)
    {
        $this->filter = $filter;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function setDataGrid(DataGrid $dataGrid)
    {
        $this->dataGrid = $dataGrid;
    }

    public function detectQueriesClassSource()
    {
        // dump('detectQueriesClassSource START => repo->getGridData');// exit;
        $gridData = $this->primaryRepository->getGridData(null, false);
        // dump($gridData);
        // dump('repo->getGridData OK');
        $this->filteredQueryClassSource = isset($gridData['filteredQueryClassSource']) ? $gridData['filteredQueryClassSource'] : null;
        $this->queryClassSource = isset($gridData['queryClassSource']) ? $gridData['queryClassSource'] : null;
        if (!isset($gridData['usedFieldNames'])) {
            $gridData['usedFieldNames'] = $gridData['tableFieldNames'];
            // dump($gridData);
        }
        $this->usedFieldNames = is_array($gridData['usedFieldNames']) ? $gridData['usedFieldNames'] : [];
    }

    public function afterSortGridData($gridData)
    {
        // dump($this->filter);
        foreach ($this->filter['orderBy'] as $filterElement) {
            // $key = BasicUtils::camelToSnakeCase($filterElement['field']);
            $key = $filterElement['field'];
            $gridData = $this->sortGridDataByParam($gridData, $key, $filterElement['direction']);
        }
        return $gridData;
    }

    public function sortGridDataByParam($gridData, $key, $direction)
    {
        // dump(App::getContainer()->getRequest()->getAll());
        // dump($key);
        // dump($gridData);
        $keys = array();
        foreach ($gridData as $index => $row)
        {
            $property = BasicUtils::snakeToCamelCase($key);
            $keys[$index] = $row[$property];
        }

        // if ($key != 'id') {
        //     dump($key);
        //     dump($keys);
        //     dump($gridData);
        // }
        if (strtoupper($direction) == 'ASC') {
            array_multisort($keys, SORT_ASC, $gridData);
        }
        if (strtoupper($direction) == 'DESC') {
            array_multisort($keys, SORT_DESC, $gridData);
        }
        // if ($key != 'id') {
        //     dump($gridData);exit;
        // }

        return $gridData;
    }

    public function mergeFilters($afterFilter, $filter)
    {
        // if (isset($afterFilter['conditions']) && is_array($afterFilter['conditions']) && isset($filter['conditions']) && is_array($filter['conditions'])) {
        //     $result['conditions'] = array_merge($afterFilter['conditions'], $filter['conditions']);
        // } elseif (is_array($afterFilter['conditions']) && $afterFilter['conditions'] != array() && (!isset($filter['conditions']) || empty($filter['conditions']))) {
        //     $result['conditions'] = $afterFilter['conditions'];
        // } elseif (is_array($filter['conditions']) && $filter['conditions'] != array() && (!isset($afterFilter['conditions']) || empty($afterFilter['conditions']))) {
        //     $result['conditions'] = $filter['conditions'];
        // }

/*
filter
======
[conditions] => Array()
    [0] => Array()
        [key] => explanation
        [value] => %szolgált%
[orderBy] => Array()
    [0] => Array()
        [field] => id
        [direction] => DESC
[maxResults] => null
[orderByStr] => ORDER BY id DESC
[currentPage] => 1
[limitStr] =>

afterFilter:
============
[username]=> Array()
    [key]=> username
    [operator]=> LIKE
    [value]=> terence
*/

        if (empty($afterFilter)) {
            $afterFilter = [];
        }

        if (empty($filter)) {
            $filter = [];
        }

        $result = [];

        if (isset($filter['conditions']) && is_array($filter['conditions'])) {
            foreach ($filter['conditions'] as $conditionRow) {
                if (!is_string($conditionRow['value'])) {
                    continue;
                }
                $property = BasicUtils::snakeToCamelCase($conditionRow['key']);
                $operator = '=';
                if (is_string($conditionRow['value']) && strpos($conditionRow['value'], '%') !== false ) {
                    $operator = 'LIKE';
                    $conditionRow['value'] = trim($conditionRow['value'], '%');
                }
                $result[$property] = [
                    'key' => $property,
                    'operator' => $operator,
                    'value' => $conditionRow['value']
                ];
            }
        }

        $result = array_merge($result, $afterFilter);
        return $result;
    }

    public function processStrategiesOnGridData($gridData, $afterFilterOnly = false)
    {
        $propertyValueProcessStrategies = $this->dataGrid->getPropertyValueProcessStrategies();
        $nonRegularStrategyFound = false;

        foreach ($this->columnParams as $column) {
            $key = $column['propertyName'];
            if (isset($propertyValueProcessStrategies[$key]) && $propertyValueProcessStrategies[$key] != 'regular') {
                $nonRegularStrategyFound = true;
            }
        }

        if ($nonRegularStrategyFound == false && !$afterFilterOnly) {
            return $gridData;
        }

        // $afterFilters = $this->mergeFilters($this->dataGrid->getAfterFilters(), $this->filter);

        $resultGridData = [];

        foreach ($gridData as $gridDataIndex => $gridDataRow) {
            // $rowValues = [];
            foreach ($gridDataRow as $property => $value) {
                
                $propertyValueProcessStrategy = $this->dataGrid->getPropertyValueProcessStrategy($property);
                
                if ($propertyValueProcessStrategy != 'regular' || $afterFilterOnly) {
                    $value = $this->dataGrid->processValue($property, $value);
                }

                $resultGridData[$gridDataIndex][$property] = $value;
                // $rowValues[$property] = $value;
            }
        }

        return $resultGridData;
    }

    public function afterFilterGridData($gridData, $afterFilterOnly = false)
    {
        // $propertyValueProcessStrategies = $this->dataGrid->getPropertyValueProcessStrategies();
        // $nonRegularStrategyFound = false;

        // foreach ($this->columnParams as $column) {
        //     $key = $column['propertyName'];
        //     if (isset($propertyValueProcessStrategies[$key]) && $propertyValueProcessStrategies[$key] != 'regular') {
        //         $nonRegularStrategyFound = true;
        //     }
        // }

        // if ($nonRegularStrategyFound == false && !$afterFilterOnly) {
        //     return $gridData;
        // }

        $afterFilters = $this->mergeFilters($this->dataGrid->getAfterFilters(), $this->filter);

        $afterFilteredGridData = [];
        $currentPage = $this->dataGrid->getCurrentPage();
        $maxRowsDisplayed = $this->dataGrid->getMaxRowsDisplayed();
        $lowerCounterLimit = (($currentPage - 1) * $maxRowsDisplayed);
        $upperCounterLimit = ($currentPage * $maxRowsDisplayed) - 1;
        $acceptedRowCounter = 0;

        foreach ($gridData as $gridDataIndex => $gridDataRow) {
            $rowAccepted = true;
            $rowValues = [];
            foreach ($gridDataRow as $property => $value) {
                
                $propertyValueProcessStrategy = $this->dataGrid->getPropertyValueProcessStrategy($property);
                
                if ($propertyValueProcessStrategy != 'regular' || $afterFilterOnly) {
                    // $value = $this->dataGrid->processValue($property, $value);

                    if (isset($afterFilters[$property])) {
                        // dump('isset($afterFilters[$property])');
                        if ($afterFilters[$property]['operator'] == 'LIKE') {
                            $pos = ($value && $afterFilters[$property]['value']) ? strpos(strtoupper($value), strtoupper($afterFilters[$property]['value'])) : false;
                            // dump('LIKE pos: ');
                            // dump($pos);
                            if ($pos === false) {
                                $rowAccepted = false;
                            }
                        }
                        if ($afterFilters[$property]['operator'] == '=') {
                            if (is_array($afterFilters[$property]['value'])) {
                                if (!in_array($value, $afterFilters[$property]['value'])) {
                                    $rowAccepted = false;
                                }
                            } else {
                                if ($value != $afterFilters[$property]['value']) {
                                    $rowAccepted = false;
                                }
                            }
                        }
                    }
                }

                $rowValues[$property] = $value;
            }

            if ($rowAccepted) {
                $this->filteredIds[] = $rowValues['id'];
                if (($acceptedRowCounter >= $lowerCounterLimit) && ($acceptedRowCounter <= $upperCounterLimit)) {
                    $afterFilteredGridData[$gridDataIndex] = $rowValues;
                } 
                // else {
                //     $this->dataGrid->debug[] = 'Nem kerult be:';
                //     $this->dataGrid->debug[] = $rowValues;
                // }
                $acceptedRowCounter++;
            }
        }

        $this->dataGrid->setTotalPages(ceil($acceptedRowCounter / $maxRowsDisplayed));
        $this->totalRowsCount = count($afterFilteredGridData);

        return $afterFilteredGridData;
    }

    public function collectMultiselectValues()
    {
        foreach ($this->dataGrid->getPropertyInputTypes() as $property => $defaultPropertyInputType) {
            // dump($property);
            if ($this->dataGrid->propertyExists($property)) {
                // dump('exists');
                if ($defaultPropertyInputType == 'multiselect') {
                    $unprocessedValues = $this->primaryRepository->getGridDataFieldUniqueValues(BasicUtils::camelToSnakeCase($property));
                    // $unprocessedValues = $this->primaryRepository->getGridDataFieldUniqueValues($property);
                    // $convertedValues = [];
                    $multiselectValues = [];
                    // dump($unprocessedValues);
                    foreach ($unprocessedValues as $unprocessedValue) {
                        $processedValue = $this->dataGrid->processValue($property, $unprocessedValue);
                        // dump($processedValue);
                        $value = $this->dataGrid->useUnprocessedAsInputValue($property) ? $unprocessedValue : $processedValue;
                        $displayed = $processedValue;
                        $multiselectValues[] = [
                            'value' => $value,
                            'displayed' => $displayed
                        ];
                    }
                    $displayed = '';
                    $this->multiselectValues[$property] = $multiselectValues;
                }
            }
        }
    }

    public function createDataArray()
    {
        $gridData = $this->primaryRepository->getGridData($this->filter);
        // dump($this->filter);
        // dump($this);
        // dump($gridData);exit;
        if ($this->dataGrid->hasValueConversion()) {
            $newDataArray = [];
            // dump($gridData['dataArray']);exit;
            foreach ($gridData['dataArray'] as $index => $row) {
                foreach ($row as $field => $value) {
                    $property = BasicUtils::snakeToCamelCase($field);
                    // dump($row);
                    $setConversion = $this->dataGrid->getValueConversion($property, $value);
                    // if ($this->dataGrid->copyOriginalOnValueConversion($property)) {
                    //     $newDataArray[$index][$this->dataGrid->copyOriginalOnValueConversion($property)] = $value;
                    // }
                    $newDataArray[$index][$property] = $setConversion ? : $value;
                }
            }
            $gridData['dataArray'] = $newDataArray;
        }

        // dump($gridData['dataArray']);exit;
        $this->dataArray = $gridData['dataArray'];
        $this->query = isset($gridData['query']) ? $gridData['query'] : null;
        $this->dataArray = $this->processStrategiesOnGridData($this->dataArray, (!$this->query ? true : false));
        $this->dataArray = $this->afterSortGridData($this->dataArray);
        $this->dataArray = $this->afterFilterGridData($this->dataArray, (!$this->query ? true : false));
        $this->collectMultiselectValues();

        // dump($this->dataArray);exit;
// dump($this->dataArray);exit;
        // $this->totalRowsCount = $gridData['totalRowsCount'];
    }

    public function getMultiselectValues()
    {
        return $this->multiselectValues;
    }

    // public function afterFilterGridData_OLD($gridData, $afterFilterOnly = false)
    // {
    //     $propertyValueProcessStrategies = $this->dataGrid->getPropertyValueProcessStrategies();
    //     $nonRegularStrategyFound = false;

    //     foreach ($this->columnParams as $column) {
    //         $key = $column['propertyName'];
    //         if (isset($propertyValueProcessStrategies[$key]) && $propertyValueProcessStrategies[$key] != 'regular') {
    //             $nonRegularStrategyFound = true;
    //         }
    //     }

    //     if ($nonRegularStrategyFound == false && !$afterFilterOnly) {
    //         return $gridData;
    //     }

    //     $afterFilters = $this->mergeFilters($this->dataGrid->getAfterFilters(), $this->filter);

    //     $afterFilteredGridData = [];
    //     $currentPage = $this->dataGrid->getCurrentPage();
    //     $maxRowsDisplayed = $this->dataGrid->getMaxRowsDisplayed();
    //     $lowerCounterLimit = (($currentPage - 1) * $maxRowsDisplayed);
    //     $upperCounterLimit = ($currentPage * $maxRowsDisplayed) - 1;
    //     $acceptedRowCounter = 0;

    //     foreach ($gridData as $gridDataIndex => $gridDataRow) {
    //         // dump('----------------');
    //         $rowAccepted = true;
    //         $rowValues = [];
    //         foreach ($gridDataRow as $fieldName => $value) {
    //             $property = BasicUtils::snakeToCamelCase($fieldName);
                
    //             $propertyValueProcessStrategy = $this->dataGrid->getPropertyValueProcessStrategy($property);
                
    //             if ($propertyValueProcessStrategy != 'regular' || $afterFilterOnly) {
    //                 $value = $this->dataGrid->processValue($property, $value);

    //                 if (isset($afterFilters[$property])) {
    //                     // dump('isset($afterFilters[$property])');
    //                     if ($afterFilters[$property]['operator'] == 'LIKE') {
    //                         $pos = strpos(strtoupper($value), strtoupper($afterFilters[$property]['value']));
    //                         // dump('LIKE pos: ');
    //                         // dump($pos);
    //                         if ($pos === false) {
    //                             $rowAccepted = false;
    //                         }
    //                     }
    //                     if ($afterFilters[$property]['operator'] == '=') {
    //                         if (is_array($afterFilters[$property]['value'])) {
    //                             if (!in_array($value, $afterFilters[$property]['value'])) {
    //                                 $rowAccepted = false;
    //                             }
    //                         } else {
    //                             if ($value != $afterFilters[$property]['value']) {
    //                                 $rowAccepted = false;
    //                             }
    //                         }
    //                     }
    //                 }
    //             }

    //             $rowValues[$fieldName] = $value;
    //         }

    //         if ($rowAccepted) {
    //             $this->filteredIds[] = $rowValues['id'];
    //             if (($acceptedRowCounter >= $lowerCounterLimit) && ($acceptedRowCounter <= $upperCounterLimit)) {
    //                 $afterFilteredGridData[$gridDataIndex] = $rowValues;
    //             } 
    //             // else {
    //             //     $this->dataGrid->debug[] = 'Nem kerult be:';
    //             //     $this->dataGrid->debug[] = $rowValues;
    //             // }
    //             $acceptedRowCounter++;
    //         }
    //     }
        
    //     $this->dataGrid->setTotalPages(ceil($acceptedRowCounter / $maxRowsDisplayed));
    //     $this->totalRowsCount = count($afterFilteredGridData);

    //     return $afterFilteredGridData;
    // }
}