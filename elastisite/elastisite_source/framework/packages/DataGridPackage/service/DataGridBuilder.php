<?php
namespace framework\packages\DataGridPackage\service;

use framework\component\parent\Service;
use framework\packages\DataGridPackage\service\DataGrid;
use framework\packages\DataGridPackage\service\GridData;
use framework\packages\DataGridPackage\service\viewService\DataGridView;
use framework\packages\DataGridPackage\service\FilterPanel;
use framework\packages\DataGridPackage\service\viewService\FilterPanelView;

class DataGridBuilder extends Service
{
    public $dataGrid;

    public function __construct($dataGridId)
    {
        $this->wireService('DataGridPackage/service/DataGrid');
        $this->wireService('DataGridPackage/service/GridData');
        $this->wireService('DataGridPackage/service/viewService/DataGridView');
        $this->wireService('DataGridPackage/service/FilterPanel');
        $this->wireService('DataGridPackage/service/viewService/FilterPanelView');

        $this->dataGrid = new DataGrid();
        $this->dataGrid->setDataGridId($dataGridId);
        $this->dataGrid->processCallingMethod(debug_backtrace());
        $this->dataGrid->setGridData(new GridData());
        $this->dataGrid->setDataGridView(new DataGridView());
        $this->dataGrid->getDataGridView()->setFilterPanelView(new FilterPanelView());
        $this->dataGrid->getGridData()->setDataGrid($this->dataGrid);
    }

    public function setPrimaryRepository($primaryRepository)
    {
        $this->dataGrid->setPrimaryRepository($primaryRepository);
    }

    public function setCreateNewText($createNewText)
    {
        $this->dataGrid->setCreateNewText($createNewText);
    }

    // public function getCreateNewText()
    // {
    //     return $this->createNewText;
    // }

    public function setLabel($label)
    {
        $this->dataGrid->setLabel($label);
    }

    // public function setDataGridId($dataGridId)
    // {
    //     $this->dataGrid->setDataGridId($dataGridId);
    // }

    public function getDataGrid() : DataGrid
    {
        return $this->dataGrid;
    }

    public function setValueConversion(array $array)
    {
        $this->dataGrid->setValueConversion($array);
    }

    public function setListActionRoute($listActionUrl)
    {
        $url = $this->getKernelObject('RoutingHelper')->getLink($listActionUrl);
        $this->dataGrid->setListActionUrl($url);
    }

    public function setEditActionRoute($editActionRoute)
    {
        $url = $this->getKernelObject('RoutingHelper')->getLink($editActionRoute);
        $this->dataGrid->setEditActionUrl($url);
    }

    public function setDeleteActionRoute($deleteActionUrl)
    {
        $url = $this->getKernelObject('RoutingHelper')->getLink($deleteActionUrl);
        $this->dataGrid->setDeleteActionUrl($url);
    }

    public function addPropertyValueProcessStrategy($property, $strategy)
    {
        $this->dataGrid->addPropertyValueProcessStrategy($property, $strategy);
    }

    public function addPropertyInputType($property, $inputType)
    {
        $this->dataGrid->addPropertyInputType($property, $inputType);
    }

    public function addUseUnprocessedAsInputValue($property)
    {
        $this->dataGrid->addUseUnprocessedAsInputValue($property);
    }

    public function setDeleteDisabled($deleteDisabled)
    {
        $this->dataGrid->setDeleteDisabled($deleteDisabled);
    }
}