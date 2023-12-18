<?php
namespace framework\packages\DataGridPackage\service\viewService;

use framework\component\parent\WidgetController;
use framework\kernel\utility\FileHandler;
use framework\packages\DataGridPackage\service\viewService\FilterPanelView;

class DataGridView extends WidgetController
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

    public function render($tableData, $preload, $wrapWithWidgetWrapper = true)
    {
        // dump($tableData);exit;
        $viewPath = 'framework/packages/DataGridPackage/view/widget/DataGrid/'.($preload === true ? 'widget' : 'widgetFlexibleContent').'.php';

        // $pagerViewPath = FileHandler::completePath('framework/packages/UXPackage/view/dashkit/tableParts/pager.php', 'source');
        // $views['pager'] = $this->renderWidget('DataGrid_pager', $pagerViewPath, [
        //     'currentPage' => $tableData['currentPage']
        // ]);

        $response = [
            'view' => $this->renderWidget('DataGrid', $viewPath, [
                'tableData' => $tableData
            ])
        ];

        // $response = [
        //     'view' => $this->renderWidget('DataGrid', $viewPath, [
        //         'preloadRenderedHtml' => $dataGrid->getPreloadRenderedHtml(),
        //         'createNewText' => $dataGrid->getCreateNewText(),
        //         'filteredIds' => $dataGrid->getGridData()->getFilteredIds(),
        //         'repository' => $dataGrid->getGridData()->getPrimaryRepository(),
        //         'wrapWithWidgetWrapper' => $wrapWithWidgetWrapper,
        //         'container' => $this->getContainer(),
        //         'dataGrid' => $dataGrid,
        //         'currentPage' => $dataGrid->getCurrentPage(),
        //         'totalPages' => $dataGrid->getTotalPages(),
        //         'listActionUrl' => $dataGrid->getListActionUrl(),
        //         'newActionUrl' => $dataGrid->getNewActionUrl(),
        //         'editActionUrl' => $dataGrid->getEditActionUrl(),
        //         'deleteActionUrl' => $dataGrid->getDeleteActionUrl(),
        //         'columnParams' => $dataGrid->getGridData()->getColumnParams(),
        //         'dataArray' => $dataGrid->getGridData()->getDataArray(),
        //         'requestKeyPrefix' => $dataGrid->getRequestKeyPrefix(),
        //         'dataGridId' => $dataGrid->getDataGridId(),
        //         'showId' => false, /* @todo */ 
        //         'allowManualOrder' => true,
        //         'orderByField' => $dataGrid->getGridData()->getFilter()['orderBy'][0]['field'],
        //         'orderByDirection' => $dataGrid->getGridData()->getFilter()['orderBy'][0]['direction'],
        //         'addDeleteLink' => false,
        //         'multiselectValues' => $dataGrid->getGridData()->getMultiselectValues(),
        //         'javaScriptOnDeleteConfirmed' => $dataGrid->getJavaScriptOnDeleteConfirmed(),
        //         //'filterPosts' => $dataGrid->getGridData()->getFilterPosts(),
        //         'conditionPosts' => $dataGrid->getGridData()->getConditionPosts(),
        //         'usedFieldNames' => $dataGrid->getGridData()->getUsedFieldNames()
        //     ]),
        //     'data' => [
        //         'label' => $dataGrid->getLabel() ? $dataGrid->getLabel() : ''
        //     ]
        // ];

        //dump($response);exit;

        return $response;
    }

    // public function render_OLD($dataGrid, $wrapWithWidgetWrapper = true)
    // {
    //     $viewPath = 'framework/packages/DataGridPackage/view/widget/DataGrid/'.($dataGrid->preload() === true ? 'widget' : 'dataGridParts').'.php';
    //     $response = [
    //         'view' => $this->renderWidget('DataGrid', $viewPath, [
    //             'preloadRenderedHtml' => $dataGrid->getPreloadRenderedHtml(),
    //             'createNewText' => $dataGrid->getCreateNewText(),
    //             'filteredIds' => $dataGrid->getGridData()->getFilteredIds(),
    //             'repository' => $dataGrid->getGridData()->getPrimaryRepository(),
    //             'wrapWithWidgetWrapper' => $wrapWithWidgetWrapper,
    //             'container' => $this->getContainer(),
    //             'dataGrid' => $dataGrid,
    //             'currentPage' => $dataGrid->getCurrentPage(),
    //             'totalPages' => $dataGrid->getTotalPages(),
    //             'listActionUrl' => $dataGrid->getListActionUrl(),
    //             'newActionUrl' => $dataGrid->getNewActionUrl(),
    //             'editActionUrl' => $dataGrid->getEditActionUrl(),
    //             'deleteActionUrl' => $dataGrid->getDeleteActionUrl(),
    //             'columnParams' => $dataGrid->getGridData()->getColumnParams(),
    //             'dataArray' => $dataGrid->getGridData()->getDataArray(),
    //             'requestKeyPrefix' => $dataGrid->getRequestKeyPrefix(),
    //             'dataGridId' => $dataGrid->getDataGridId(),
    //             'showId' => false, /* @todo */ 
    //             'allowManualOrder' => true,
    //             'orderByField' => $dataGrid->getGridData()->getFilter()['orderBy'][0]['field'],
    //             'orderByDirection' => $dataGrid->getGridData()->getFilter()['orderBy'][0]['direction'],
    //             'addDeleteLink' => false,
    //             'multiselectValues' => $dataGrid->getGridData()->getMultiselectValues(),
    //             'javaScriptOnDeleteConfirmed' => $dataGrid->getJavaScriptOnDeleteConfirmed(),
    //             //'filterPosts' => $dataGrid->getGridData()->getFilterPosts(),
    //             'conditionPosts' => $dataGrid->getGridData()->getConditionPosts(),
    //             'usedFieldNames' => $dataGrid->getGridData()->getUsedFieldNames()
    //         ]),
    //         'data' => [
    //             'label' => $dataGrid->getLabel() ? $dataGrid->getLabel() : ''
    //         ]
    //     ];
    //     return $response;
    // }
}