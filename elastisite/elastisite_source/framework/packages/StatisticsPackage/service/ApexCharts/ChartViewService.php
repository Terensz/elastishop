<?php
namespace framework\packages\StatisticsPackage\service\ApexCharts;

use framework\kernel\utility\BasicUtils;
use framework\component\parent\WidgetController;

class ChartViewService extends WidgetController
{
    protected $chartId;
    protected $chartType;
    protected $multiple = true;
    protected $chartData;
    protected $chartTitle;
    protected $valueAxisTitle;
    protected $categoryAxisTitle;

    public function setChartId($chartId)
    {
        $this->chartId = $chartId;
    }

    public function getChartId()
    {
        return $this->chartId;
    }

    public function setChartType($chartType)
    {
        $this->chartType = $chartType;
    }

    public function getChartType()
    {
        return $this->chartType;
    }

    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;
    }

    public function getMultiple()
    {
        return $this->multiple;
    }

    public function setChartData($chartData)
    {
        $this->chartData = $chartData;
    }

    public function getChartData()
    {
        return $this->chartData;
    }

    public function setChartTitle($chartTitle)
    {
        $this->chartTitle = $chartTitle;
    }

    public function setValueAxisTitle($valueAxisTitle)
    {
        $this->valueAxisTitle = $valueAxisTitle;
    }

    public function setCategoryAxisTitle($categoryAxisTitle)
    {
        $this->categoryAxisTitle = $categoryAxisTitle;
    }
    
    public function render()
    {
        $jsViewName = ($this->multiple ? 'multiple' : 'single').'_'.$this->chartType;
        $viewPath = 'framework/packages/StatisticsPackage/view/ApexCharts/jsView/'.$jsViewName.'.php';

        $viewData = [
            // 'container' => $this->getContainer(),
            'chartId' => $this->chartId,
            'chartType' => $this->chartType,
            'chartData' => $this->chartData,
            'multiple' => $this->multiple,
            'chartTitle' => $this->chartTitle,
            'valueAxisTitle' => $this->valueAxisTitle,
            'categoryAxisTitle' => $this->categoryAxisTitle
        ];
        // dump($this->chartData);exit;
        return $this->renderWidget('AdminVisitsAndPageLoadsWidget', $viewPath, $viewData);
    }
}
