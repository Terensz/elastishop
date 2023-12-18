<?php
namespace framework\packages\DataGridPackage\service;

use App;
use framework\component\parent\Service;
use framework\kernel\utility\BasicUtils;
use framework\packages\DataGridPackage\service\GridData;
use framework\packages\DataGridPackage\service\viewService\DataGridView;
use framework\packages\DataGridPackage\service\viewService\FilterPanelView;
use framework\packages\DataGridPackage\service\viewService\TableDataExtractor;

class DataGrid extends Service
{
    public $dumpDebug = false;

    private $label;

    private $javaScriptOnDeleteConfirmed = '';

    private $createNewText;

    private $maxRowsDisplayed = 10;

    private $currentPage = 1;

    private $totalPages = 1;

    public $debug = [];

    private $deleteDisabled = false;

    private $configured = false;

    /**
     * First load is preload, any more ajax submits will not be preloads.
    */
    private $preload = true;

    private $preloadRenderedHtml;

    private $valueConversion = [];

    private $listActionUrl;

    private $newActionUrl;

    private $editActionUrl;

    private $deleteActionUrl;

    private $requestKeyPrefix;

    private $dataGridId;

    private $afterFilters = [];

    private $colorCellByValue = [
        'property' => 'status',
        'useValueConversion' => true, // false, if you want to color by processed value
        'values' => [
            '0' => [
                'background' => '1b1b1b',
                'font' => 'efefef'
            ],
            '2' => [
                'background' => '32163c',
                'font' => 'efefef'
            ],
            '3' => [
                'background' => '212d64',
                'font' => 'efefef'
            ]
        ]
    ];

    private $propertyInputTypes = [
        'permissionGroups' => 'multiselect',
        'status' => 'multiselect',
        'countryName' => 'multiselect',
        'zipCode' => 'multiselect'
    ];

    // private $propertyIdField = [
    //     'countryName' => 'countryId'
    // ];

    private $propertyValueProcessStrategies = [
        'countryName' => 'translate',
        'status' => 'translate'
    ];

    private $forceQueryFilterOnProperties = ['status'];

    private $useUnprocessedAsInputValue = ['status'];

    // private $defaultFieldInputTypes = [
    //     'country'
    // ];

    private $defaultNarrowCols = ['zip', 'zipCode'];

    private $defaultOrderByField = 'id';

    private $defaultOrderByDirection = 'DESC';

    // private $hasDeleteButton = true;

    // private $copyOriginalOnValueConversion = [
    //     'status' => 'statusId'
    // ];

    /**
     * The configuration object of the grid
    */
    private $dataGridConfiguration;

    /**
     * The handler object of the filtered data of the grid
    */
    private $gridData;

    /**
     * The renderer service of the grid. Also contains instances of the filterpanel-renderer, the chart and any other renderer classes.
    */
    private $dataGridView;

    public function __construct()
    {
        $this->createNewText = trans('create.new');
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setColorCellByValue($colorCellByValue)
    {
        $this->colorCellByValue = $colorCellByValue;
    }

    public function setJavaScriptOnDeleteConfirmed($javaScriptOnDeleteConfirmed)
    {
        $this->javaScriptOnDeleteConfirmed = $javaScriptOnDeleteConfirmed;
    }

    public function getJavaScriptOnDeleteConfirmed()
    {
        return $this->javaScriptOnDeleteConfirmed;
    }

    public function setDataGridId($dataGridId)
    {
        $this->dataGridId = $dataGridId;
    }

    public function setCreateNewText($createNewText)
    {
        $this->createNewText = $createNewText;
    }

    public function getCreateNewText()
    {
        return $this->createNewText;
    }

    public function getCellColorProperty()
    {
        if (!empty($this->colorCellByValue)) {
            return $this->colorCellByValue['property'];
        }
        return null;
    }

    public function getCellColor($row)
    {
        $property = $this->getCellColorProperty();
        if ($property && isset($row[$property])) {
            $value = $row[$property];
            foreach ($this->colorCellByValue['values'] as $configuredValue => $coloredValueConfig) {
                $configuredValue = $this->colorCellByValue['useValueConversion'] ? trans($this->getValueConversion($property, $configuredValue)) : $configuredValue;
                if ($value == $configuredValue) {
                    return $coloredValueConfig;
                }
            }
        }
        return null;
    }

    public function propertyExists($property)
    {
        //dump($this->gridData->getColumnParams());
        foreach ($this->gridData->getColumnParams() as $columnParams) {
            if ($columnParams['propertyName'] == $property) {
                return true;
            }
        }
        return false;
    }

    public function preload()
    {
        //return false;
        return $this->preload;
    }

    public function setPreloadRenderedHtml($preloadRenderedHtml)
    {
        $this->preloadRenderedHtml = $preloadRenderedHtml;
    }

    public function getPreloadRenderedHtml()
    {
        //return false;
        if ($this->preload) {
            return $this->preloadRenderedHtml;
        }
    }

    public function setDeleteDisabled($deleteDisabled)
    {
        $this->deleteDisabled = $deleteDisabled;
    }

    public function getDeleteDisabled()
    {
        return $this->deleteDisabled;
    }

    // public function getPropertyIdField($property)
    // {
    //     return isset($this->propertyIdField[$property]) ? $this->propertyIdField[$property] : null;
    // }

    public function processValue($property, $value)
    {
        $convertedValue = $this->getValueConversion($property, $value);
        $value = $convertedValue ? $convertedValue : $value;
        if ($this->getPropertyValueProcessStrategy($property) == 'translate') {
            $value = trans($value);
        } elseif ($this->getPropertyValueProcessStrategy($property) == 'decrypt') {
            $value = $this->getContainer()->getService('Crypter')->decrypt($value);
        }
        return $value;
    }

    public function addUseUnprocessedAsInputValue($property)
    {
        $this->useUnprocessedAsInputValue[] = $property;
    }
    
    public function useUnprocessedAsInputValue($property)
    {
        return in_array($property, $this->useUnprocessedAsInputValue) ? true : false;
    }

    public function setMaxRowsDisplayed($maxRowsDisplayed)
    {
        $this->maxRowsDisplayed = $maxRowsDisplayed;
    }

    public function getMaxRowsDisplayed()
    {
        return $this->maxRowsDisplayed;
    }


    public function getPropertyInputType($property)
    {
        return isset($this->propertyInputTypes[$property]) ? $this->propertyInputTypes[$property] : 'text';
    }

    public function addPropertyInputType($property, $inputType)
    {
        $this->propertyInputTypes[$property] = $inputType;
    }

    public function getPropertyInputTypes()
    {
        return $this->propertyInputTypes;
    }

    public function getPropertyValueProcessStrategy($property)
    {
        return isset($this->propertyValueProcessStrategies[$property]) ? $this->propertyValueProcessStrategies[$property] : 'regular'; 
    }

    public function getPropertyValueProcessStrategies()
    {
        return $this->propertyValueProcessStrategies;
    }

    public function addPropertyValueProcessStrategy($property, $strategy)
    {
        $this->propertyValueProcessStrategies[$property] = $strategy;
    }

    // public function hasDeleteButton()
    // {
    //     return $this->hasDeleteButton;
    // }


    public function setValueConversion(array $array)
    {
        foreach ($array as $property => $conversions) {
            foreach ($conversions as $key => $value) {
                $this->valueConversion[$property][$key] = $value;
            }
        }
    }

    public function getDefaultNarrowCols()
    {
        return $this->defaultNarrowCols;
    }

    // public function copyOriginalOnValueConversion($property)
    // {
    //     return isset($this->copyOriginalOnValueConversion[$property]) ? $this->copyOriginalOnValueConversion[$property] : null;
    // }

    public function getValueConversion($property, $key)
    {
        $result = isset($this->valueConversion[$property][$key]) ? $this->valueConversion[$property][$key] : null;

        // if ($property == 'isTester') {
        //     dump($this->valueConversion);
        //     dump($result);
        // }

        return $result;
    }

    public function hasValueConversion()
    {
        return $this->valueConversion ? true : false;
    }

    public function processCallingMethod($debugBacktrace)
    {
        $trace = $this->findRouteRenderingBacktrace($debugBacktrace);
        // dump($this->getContainer()->getWidgetMap());
        //dump($this->getContainer()->getFullRouteMap());
        $route = $this->getContainer()->getKernelObject('RoutingHelper')->findRouteByMethod($trace['classRoute'], $trace['method']);
        $this->listActionUrl = $this->getContainer()->getKernelObject('RoutingHelper')->getLink($route['name']);
        //dump($route);exit;
        if (!$this->newActionUrl) {
            $this->newActionUrl = $this->findActionUrl($trace, 'New');
        }
        if (!$this->editActionUrl) {
            $this->editActionUrl = $this->findActionUrl($trace, 'Edit');
        }
        if (!$this->deleteActionUrl) {
            $this->deleteActionUrl = $this->findActionUrl($trace, 'Delete');
        }
        
        $this->requestKeyPrefix = str_replace('WidgetAction', '', $trace['method']);
        
        if (!$this->dataGridId) {
            $this->dataGridId = ucfirst($this->requestKeyPrefix).'Grid';
        }
    }

    public function findActionUrl($trace, $methodType)
    {
        $actionBase = str_replace('WidgetAction', '', $trace['method']);
        $actionBase = str_replace('ListAction', '', $actionBase);
        $route = $this->getContainer()->getKernelObject('RoutingHelper')->findRouteByMethod($trace['classRoute'], rtrim($actionBase, 'ies').'y'.$methodType.'Action');
        if (!$route) {
            $route = $this->getContainer()->getKernelObject('RoutingHelper')->findRouteByMethod($trace['classRoute'], rtrim($actionBase, 'es').$methodType.'Action');
        }
        if (!$route) {
            $route = $this->getContainer()->getKernelObject('RoutingHelper')->findRouteByMethod($trace['classRoute'], rtrim($actionBase, 's').$methodType.'Action');
            // dump($trace['classRoute'].' / '.rtrim($actionBase, 's').$methodType.'Action');
        }
        if (!$route) {
            $route = $this->getContainer()->getKernelObject('RoutingHelper')->findRouteByMethod($trace['classRoute'], $actionBase.$methodType.'Action');
        }
        // dump(rtrim($actionBase, 'es').$methodType.'Action');exit;
        if (!$route) {
            return false;
        }
        return $this->getContainer()->getKernelObject('RoutingHelper')->getLink($route['name']);
    }

    public function getListActionUrl()
    {
        return $this->listActionUrl;
    }

    public function getAfterFilters()
    {
        return $this->afterFilters;
    }

    public function setListActionUrl($listActionUrl)
    {
        $this->listActionUrl = $listActionUrl;
    }

    public function setNewActionUrl($newActionUrl)
    {
        $this->newActionUrl = $newActionUrl;
    }

    public function getNewActionUrl()
    {
        return $this->newActionUrl;
    }

    public function setEditActionUrl($editActionUrl)
    {
        $this->editActionUrl = $editActionUrl;
    }

    public function getEditActionUrl()
    {
        return $this->editActionUrl;
    }

    public function setDeleteActionUrl($deleteActionUrl)
    {
        $this->deleteActionUrl = $deleteActionUrl;
    }

    public function getDeleteActionUrl()
    {
        return $this->deleteActionUrl;
    }

    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
        // $filter = $this->gridData->getFilter();
        // if (!isset($filter['currentPage'])) {
        //     return 1;
        // }
        // return $filter['currentPage'];
    }

    public function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;
    }

    public function getTotalPages()
    {
        return $this->totalPages;
    }

    public function findRouteRenderingBacktrace($debugBacktrace)
    {
        foreach ($debugBacktrace as $trace) {
            $fileName = isset($trace['file']) ? BasicUtils::explodeAndGetElement($trace['file'], '/', 'last') : null;
            if ($fileName == 'RouteRendering.php') {
                if (isset($trace['args']) && isset($trace['args'][0]) && isset($trace['args'][0][0]) && isset($trace['args'][0][1])) {
                    //dump($trace['args']);
                    return [
                        'classRoute' => str_replace('\\', '/', get_class($trace['args'][0][0])),
                        'method' => $trace['args'][0][1],
                    ];
                }
            }
        }
    }

    public function setRequestKeyPrefix($requestKeyPrefix)
    {
        $this->requestKeyPrefix = $requestKeyPrefix;
        //$this->configure();
    }

    public function getRequestKeyPrefix()
    {
        return $this->requestKeyPrefix;
    }

    public function getDataGridId()
    {
        return $this->dataGridId;
        //$this->configure();
    }

    // public function setDataGridConfiguration(DataGridView $dataGridConfiguration)
    // {
    //     $this->dataGridConfiguration = $dataGridConfiguration;
    // }

    public function configure(array $configArray = null)
    {
        if ($this->configured) {
            return true;
        }
        if (!$this->gridData->getPrimaryRepository()) {
            return false;
        }
        if ($configArray && is_array($configArray)) {
            foreach ($configArray as $configKey => $configValue) {
                if ($configKey == 'requestKeyPrefix') {
                    $this->requestKeyPrefix = $configValue;
                }
                if ($configKey == 'defaultOrderByField') {
                    $this->defaultOrderByField = $configValue;
                }
                if ($configKey == 'defaultOrderByDirection') {
                    $this->defaultOrderByDirection = $configValue;
                }
            }
        }
        $this->createQueryFilter();
        $this->configured = true;
    }

    public function getConfig($key)
    {
        return isset($this->dataGridConfiguration[$key]) ? $this->dataGridConfiguration[$key] : null;
    }

    private function createQueryFilter()
    {
        // $filter = array();
        $filterConditions = array();
        $orderByProp = null;
        $orderByDirection = null;
        $maxResults = null;
        $currentPage = 1;
        $conditionPosts = $this->gridData->getConditionPosts();
        // $this->debug[] = $conditionPosts;
        // dump($conditionPosts);exit;
        // dump($this->getRequest()->getAll());//exit;
        if (is_array($this->getRequest()->getAll())) {
            foreach ($this->getRequest()->getAll() as $key => $value) {
                if ($key == 'preload') {
                    $this->preload = ($value == 'false' ? false : ($value == 'true' || $value === true ? true : $value));
                }
                if (in_array($key, array('maxResults', 'currentPage', 'orderByProp', 'orderByDirection'))) {
                    ${$key} = $value;
                }
                $dataGridId = BasicUtils::explodeAndRemoveElement($key, '_', 'last');
                $loopRequestKey = BasicUtils::explodeAndGetElement($key, '_', 'last');
                // dump('=======================');
                // dump($loopRequestKey);
                // dump($dataGridId);
                // dump($this->dataGridId);
                // dump($value);
                // dump('---------------------');
                // $loopColumnName = BasicUtils::camelToSnakeCase($loopRequestKey);
                // $this->debug[] = $conditionPosts;
                // $this->debug[] = $loopRequestKey;
                // $this->debug[] = $dataGridId;
                // $this->debug[] = $this->requestKeyPrefix;
                // $this->debug[] = $value;
                //$this->debug[] = $value;
                // dump($this->dataGridId);
                // dump($dataGridId);dump($value); //exit;
                if ($dataGridId == $this->dataGridId && $value !== null && $value !== '') {
                    // dump('bent!');
                    // $this->debug[] = 'bent!';
                    // if (in_array($loopRequestKey, array('maxResults', 'currentPage', 'orderByProp', 'orderByDirection'))) {
                    //     ${$loopRequestKey} = $value;
                    // } else {
                    //     if (isset($posts[$loopRequestKey])) {
                    //         $this->gridData->setConditionPostValue($loopRequestKey, $value);
                    //         if ($posts[$loopRequestKey]['type'] == 'normal') {
                    //             $filterConditions[] = array('key' => $posts[$loopRequestKey]['columnName'], 'value' => $value);
                    //         } elseif ($posts[$loopRequestKey]['type'] == 'dateStart') {
                    //             $filterConditions[] = array('key' => $posts[$loopRequestKey]['columnName'], 'operator' => '>', 'value' => $value);
                    //         } elseif ($posts[$loopRequestKey]['type'] == 'dateEnd') {
                    //             $filterConditions[] = array('key' => $posts[$loopRequestKey]['columnName'], 'operator' => '<', 'value' => $value);
                    //         }
                    //     } else {
                    //         /**
                    //          * @todo penalty
                    //         */
                    //     }
                    // }
                    //$this->debug[] = $loopRequestKey;
                    // dump($loopRequestKey);exit;
                    if (isset($conditionPosts[$loopRequestKey])) {
                        // dump('most!!! '.$loopRequestKey);
                        // dump("conditionPosts[$loopRequestKey]['type']: ".$conditionPosts[$loopRequestKey]['type']);
                        //$this->gridData->setConditionPostValue($loopRequestKey, $value);
                        $hasNonRegularProcessStrategy = $this->getPropertyValueProcessStrategy($conditionPosts[$loopRequestKey]['propertyName']) != 'regular';
                        // $this->debug[] = $conditionPosts[$loopRequestKey]['propertyName'];
                        // $this->debug[] = $hasNonRegularProcessStrategy;
                        // $this->debug[] = !in_array($conditionPosts[$loopRequestKey]['propertyName'], $this->forceQueryFilterOnProperties);
                        // $this->debug[] = '-------------------------------';
                        if (!$hasNonRegularProcessStrategy || ($hasNonRegularProcessStrategy && in_array($conditionPosts[$loopRequestKey]['propertyName'], $this->forceQueryFilterOnProperties))) {
                            // dump('v1');
                            if ($conditionPosts[$loopRequestKey]['type'] == 'text') {
                                $filterConditions[] = array('key' => $conditionPosts[$loopRequestKey]['columnName'], 'value' => '%'.$value.'%');
                            } elseif ($conditionPosts[$loopRequestKey]['type'] == 'date') {
                                $dateParts = explode(' - ', $value);
                                if (count($dateParts) == 2) {
                                    $date1 = (new \DateTime($dateParts[0]))->format('Y-m-d H:i:s');
                                    $date2 = (new \DateTime($dateParts[1]))->format('Y-m-d H:i:s');
    
                                    $filterConditions[] = array('key' => $conditionPosts[$loopRequestKey]['columnName'], 'operator' => '>', 'value' => $date1);
                                    $filterConditions[] = array('key' => $conditionPosts[$loopRequestKey]['columnName'], 'operator' => '<', 'value' => $date2);
                                }
                            } elseif ($conditionPosts[$loopRequestKey]['type'] == 'multiselect') {
                                // foreach ($value as $valuePiece) {
                                //     $filterConditions[] = array('key' => $conditionPosts[$loopRequestKey]['columnName'], 'operator' => '=', 'value' => $valuePiece);
                                // }
                                $filterConditions[] = array('key' => $conditionPosts[$loopRequestKey]['columnName'], 'operator' => '=', 'value' => $value);
                            }
                        } else {
                            // dump('v2');
                            if ($conditionPosts[$loopRequestKey]['type'] == 'text') {
                                $this->afterFilters[$conditionPosts[$loopRequestKey]['propertyName']] = [
                                    'key' => $conditionPosts[$loopRequestKey]['propertyName'],
                                    'operator' => 'LIKE',
                                    'value' => $value
                                ];
                            } elseif ($conditionPosts[$loopRequestKey]['type'] == 'multiselect') {
                                $this->afterFilters[$conditionPosts[$loopRequestKey]['propertyName']] = [
                                    'key' => $conditionPosts[$loopRequestKey]['propertyName'],
                                    'operator' => '=',
                                    'value' => $value
                                ];
                                // foreach ($value as $valueElement) {
                                //     $this->afterFilters[$conditionPosts[$loopRequestKey]['propertyName']] = [
                                //         'key' => $conditionPosts[$loopRequestKey]['propertyName'],
                                //         'operator' => '=',
                                //         'value' => $valueElement
                                //     ];
                                // }
                            }
                            // dump($this->afterFilters);
                        }
                    } else {
                        /**
                         * @todo penalty
                        */
                    }
                    //dump($conditionPosts);exit;
                }
            }
            // exit;
        }
        //$this->debug[] = $filterConditions;
        $this->currentPage = $currentPage;

        $orderByField = BasicUtils::camelToSnakeCase($orderByProp);
        $filter = [
            'conditions' => $filterConditions,
            // 'conditions' => [],
            'orderBy' => $orderByField 
                ? [['field' => $orderByField, 'direction' => ($orderByDirection ? : 'ASC')]]
                : [['field' => $this->defaultOrderByField, 'direction' => $this->defaultOrderByDirection]],
            'maxResults' => $maxResults
            // 'currentPage' => $currentPage
        ];
        $filter = (new \framework\component\parent\DbRepository())->transformFilter($filter);
        // dump($this->getRequest()->getAll());
        // dump($filter);
        $this->gridData->setFilter($filter);
    }

    public function setGridData(GridData $gridData)
    {
        $this->gridData = $gridData;
    }

    public function getGridData() : GridData
    {
        return $this->gridData;
    }

    public function setPrimaryRepository($primaryRepository)
    {
        $this->getGridData()->setPrimaryRepository($primaryRepository);
        // dump('setPrimaryRepository');
        $this->gridData->detectQueriesClassSource();
        // dump('detectQueriesClassSource OK');
        $this->gridData->createColumnList();
        // dump('createColumnList OK');
        //dump($this);
        //$this->gridData->setDateProps();
        $this->gridData->setPostKeys();
        $this->configure();
        // dump($this);
        $this->gridData->createDataArray();
        // dump($this);//exit;
    }

    public function setDataGridView(DataGridView $dataGridView)
    {
        $this->dataGridView = $dataGridView;
    }

    public function getDataGridView() : DataGridView
    {
        return $this->dataGridView;
    }

    public function render($wrapWithWidgetWrapper = true)
    {
        // return $this->dataGridView->render($this, $wrapWithWidgetWrapper);
        // dump('render');exit;
        $tableData = $this->extractTableData();

        return $this->dataGridView->render($tableData, $this->preload(), $wrapWithWidgetWrapper);
    }

    public function extractTableData()
    {
        App::getContainer()->wireService('DataGridPackage/service/viewService/TableDataExtractor');
        $tableData = TableDataExtractor::extract($this);

        return $tableData;
    }

    // private function removeBlankFromFilter($filter)
    // {
    //     if (!$filter || !isset($filter['conditions'])) {
    //         return null;
    //     }
    //     $conditions = array();
    //     foreach ($filter['conditions'] as $condition) {
    //         if ($condition['value'] !== '' && $condition['value'] !== null) {
    //             $conditions[] = $condition;
    //         }
    //     }
    //     $filter['conditions'] = $conditions;
    //     return $filter;
    // }
}