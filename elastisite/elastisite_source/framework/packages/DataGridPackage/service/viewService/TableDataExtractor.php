<?php
namespace framework\packages\DataGridPackage\service\viewService;

use framework\component\parent\WidgetController;
use framework\kernel\utility\BasicUtils;
use framework\packages\DataGridPackage\service\viewService\FilterPanelView;

class TableDataExtractor extends WidgetController
{
    protected $filterPanelView;

    public function setFilterPanelView(FilterPanelView $filterPanelView)
    {
        $this->filterPanelView = $filterPanelView;
    }

    public function getFilterPanelView() : FilterPanelView
    {
        return $this->filterPanelView;
    }

    public static function extract($dataGrid)
    {
        // dump($dataGrid);exit;
        $currentPage = $dataGrid->getCurrentPage();
        $conditionPosts = $dataGrid->getGridData()->getConditionPosts();
        // $columnNames = $dataGrid->getGridData()->getUsedFieldNames();
        $columnParamsRaw = $dataGrid->getGridData()->getColumnParams();

        // dump($columnParams);

        $displayedProperties = [];
        $columnParams = [];
        foreach ($columnParamsRaw as $columnKey => $columnParam) {
            if ($columnParam['visible'] && $columnParam['propertyName']) {
                $displayedProperties[] = $columnParam['propertyName'];
            }
            if ($columnParam['role'] == 'deleteButton') {
                $columnParams['deleteButton'] = $columnParam;
            } else {
                $columnParams[$columnKey] = $columnParam;
            }
            // foreach ($columnNames as $columnName) {
            //     if ($columnParam['name'] == $columnName && $columnParam['visible']) {
            //         $displayedProperties[] = $columnParam['propertyName'];
            //     }
            // }
        }

        // dump($dataGrid->getGridData()->getUsedFieldNames());exit;
        $orderByField = $dataGrid->getGridData()->getFilter() ? BasicUtils::snakeToCamelCase($dataGrid->getGridData()->getFilter()['orderBy'][0]['field']) : null;
        $orderByDirection = $dataGrid->getGridData()->getFilter() ? strtoupper($dataGrid->getGridData()->getFilter()['orderBy'][0]['direction']) : null;

        $return = [
            'pager' => [
                'currentPage' => $currentPage,
                'totalPages' => $dataGrid->getTotalPages()
            ],
            'urls' => [
                'listActionUrl' => $dataGrid->getListActionUrl(),
                'newActionUrl' => $dataGrid->getNewActionUrl(),
                'editActionUrl' => $dataGrid->getEditActionUrl(),
                'deleteActionUrl' => $dataGrid->getDeleteActionUrl()
            ],
            'texts' => [
                'createNewText' => $dataGrid->getCreateNewText()
            ],
            'configuration' => [
                'columnParams' => $columnParams,
                'displayedProperties' => $displayedProperties,
                'showId' => false, /* @todo */ 
                'allowManualOrder' => true,
                'multiselectValues' => $dataGrid->getGridData()->getMultiselectValues(),
                'javaScriptOnDeleteConfirmed' => $dataGrid->getJavaScriptOnDeleteConfirmed()
            ],
            'search' => [
                'conditionPosts' => $dataGrid->getGridData()->getConditionPosts(),
                'orderByField' => $orderByField,
                'orderByDirection' => $orderByDirection
            ],
            // 'conditionPosts' => $conditionPosts,
            // 'columnNames' => $columnNames,
            'data' => [
                'dataGridId' => $dataGrid->getDataGridId(),
                'gridData' => $dataGrid->getGridData()->getDataArray()
            ]
        ];

        // dump($return);exit;

        return $return;

        // dump($return);exit;
        // dump($dataGrid);exit;


        // $viewPath = 'framework/packages/DataGridPackage/view/widget/DataGrid/'.($dataGrid->preload() === true ? 'widget' : 'dataGridParts').'.php';
        // $response = [
        //     'view' => $this->renderWidget('DataGrid', $viewPath, [
        //         'preloadRenderedHtml' => $dataGrid->getPreloadRenderedHtml(),
        //         'createNewText' => $dataGrid->getCreateNewText(),
        //         'filteredIds' => $dataGrid->getGridData()->getFilteredIds(),
        //         'repository' => $dataGrid->getGridData()->getPrimaryRepository(),
        //         'wrapWithWidgetWrapper' => $wrapWithWidgetWrapper,
        //         'container' => $this->getContainer(),
        //         'dataGrid' => $dataGrid,
        //         // 'currentPage' => $dataGrid->getCurrentPage(),
        //         // 'totalPages' => $dataGrid->getTotalPages(),
        //         // 'listActionUrl' => $dataGrid->getListActionUrl(),
        //         // 'newActionUrl' => $dataGrid->getNewActionUrl(),
        //         // 'editActionUrl' => $dataGrid->getEditActionUrl(),
        //         // 'deleteActionUrl' => $dataGrid->getDeleteActionUrl(),
        //         // 'columnParams' => $dataGrid->getGridData()->getColumnParams(),
        //         // 'dataArray' => $dataGrid->getGridData()->getDataArray(),
        //         'requestKeyPrefix' => $dataGrid->getRequestKeyPrefix(),
        //         'dataGridId' => $dataGrid->getDataGridId(),
        //         'showId' => false, /* @todo */ 
        //         'allowManualOrder' => true,
        //         // 'orderByField' => $dataGrid->getGridData()->getFilter()['orderBy'][0]['field'],
        //         // 'orderByDirection' => $dataGrid->getGridData()->getFilter()['orderBy'][0]['direction'],
        //         'addDeleteLink' => false,
        //         // 'multiselectValues' => $dataGrid->getGridData()->getMultiselectValues(),
        //         'javaScriptOnDeleteConfirmed' => $dataGrid->getJavaScriptOnDeleteConfirmed(),
        //         //'filterPosts' => $dataGrid->getGridData()->getFilterPosts(),
        //         // 'conditionPosts' => $dataGrid->getGridData()->getConditionPosts(),
        //         'usedFieldNames' => $dataGrid->getGridData()->getUsedFieldNames()
        //     ]),
        //     'data' => [
        //         'label' => $dataGrid->getLabel() ? $dataGrid->getLabel() : ''
        //     ]
        // ];

        // //dump($response);exit;

        // return $response;
    }
}