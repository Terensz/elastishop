<?php
namespace framework\packages\VisitorPackage\controller;

use framework\component\parent\WidgetController;
use framework\packages\VisitorPackage\repository\VisitRepository;
use framework\packages\SeoPackage\repository\SearchedKeywordRepository;
use framework\packages\StatisticsPackage\service\ApexCharts\ChartDataService;
use framework\packages\StatisticsPackage\service\ApexCharts\ChartViewService;
use framework\packages\VisitorPackage\service\VisitorService;
use framework\component\helper\DateUtils;

class VisitorWidgetController extends WidgetController
{
    /**
    * Route: [name: admin_visitsAndPageLoads_widget, paramChain: /admin/visitsAndPageLoads/widget]
    */
    public function adminVisitsAndPageLoadsWidgetAction()
    {
        // $this->getContainer()->setService('VisitorPackage/service/RefererUrlService');
        // $refererUrlService = $this->getContainer()->getService('RefererUrlService');
        // dump($refererUrlService);exit;

        $this->getContainer()->wireService('VisitorPackage/repository/VisitRepository');
        // $chartService = new ChartService();

        // $visitRepo = new VisitRepository();
        // $stats = $visitRepo->getStatsByDay(0);
        // $chart1View = new ChartViewService();
        // $chart1View->setChartId('chart1');
        // $chart1View->setChartTitle(trans('total.site.and.page.visits').' - '.$stats['currentMonthName']);
        // $chart1View->setValueAxisTitle(trans('number.of.visits'));
        // $chart1View->setCategoryAxisTitle(trans('days.of.period'));
        // $chart1View->setChartType('line');
        // $chart1View->setMultiple(true);
        // $chart1View->setChartData(ChartDataService::createData($stats['result'], ['total_page_loads', 'total_visitors'], 'visited_at', [], $stats['periodStartDate'], $stats['periodEndDate']));
        // $renderedChart1View = $chart1View->render();
        // dump($chart1View);exit;
        $visitRepo = new VisitRepository();
        $earliestDate = $visitRepo->getEarliestDate();
        $earliestDateTime = new \DateTime($earliestDate);

        // dump($earliestDateTime->format('Y-m-d H:i:s'));exit;

        $periodMonthProperties = DateUtils::getPeriodMonthProperties($earliestDateTime, new \DateTime());
        $minMonthIndex = $periodMonthProperties[0]['monthIndex'];
        $maxMonthIndex = $periodMonthProperties[count($periodMonthProperties) - 1]['monthIndex'];
        // dump($periodMonthProperties);exit;
        // dump($earliestDateTime->format('Y-m'));
        // dump($earliestDateTime->format('Y-m-d H:i:s'));
        // exit;

        $viewPath = 'framework/packages/VisitorPackage/view/widget/AdminVisitsAndPageLoadsWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminVisitsAndPageLoadsWidget', $viewPath, [
                'container' => $this->getContainer(),
                'periodMonthProperties' => $periodMonthProperties,
                'minMonthIndex' => $minMonthIndex,
                'maxMonthIndex' => $maxMonthIndex,
                'chart1' => $this->getRenderedChartView(0, 'chart1'),
                'chart2' => $this->getRenderedChartView(-1, 'chart2')
            ]),
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_visitsAndPageLoads_earlierMonth, paramChain: /admin/visitsAndPageLoads/earlierMonth]
    */
    public function adminVisitsAndPageLoadsEarlierMonthAction()
    {
        $monthIndex = $this->getContainer()->getRequest()->get('monthIndex');

        $view = $monthIndex == 'null' ? '' : $this->getRenderedChartView($monthIndex, 'chart3');
        $response = [
            'view' => $view,
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    public function getRenderedChartView($periodStartIndex, $chartId)
    {
        $this->getContainer()->wireService('VisitorPackage/repository/VisitRepository');
        $this->getContainer()->wireService('StatisticsPackage/service/ApexCharts/ChartDataService');
        $this->getContainer()->wireService('StatisticsPackage/service/ApexCharts/ChartViewService');
        $visitRepo = new VisitRepository();
        $stats = $visitRepo->getStatsByDay($periodStartIndex);
        $chartView = new ChartViewService();
        $chartView->setChartId($chartId);
        $chartView->setChartTitle(trans('total.site.and.page.visits').' - '.$stats['currentMonthName']);
        $chartView->setValueAxisTitle(trans('number.of.visits'));
        $chartView->setCategoryAxisTitle(trans('days.of.period'));
        $chartView->setChartType('line');
        $chartView->setMultiple(true);
        $chartView->setChartData(ChartDataService::createData($stats['result'], ['total_page_loads', 'total_visitors'], 'visited_at', [], $stats['periodStartDate'], $stats['periodEndDate']));
        // dump($chart1View->getChartData());dump($stats);exit;
        return $chartView->render();
    }

    /**
    * Route: [name: admin_mostUsedKeywords_widget, paramChain: /admin/mostUsedKeywords/widget]
    */
    public function adminMostUsedKeywordsWidgetAction()
    {
        $this->getContainer()->wireService('VisitorPackage/service/VisitorService');
        $this->getContainer()->wireService('SeoPackage/repository/SearchedKeywordRepository');
        $keywordRepo = new SearchedKeywordRepository();
        $keywords = $keywordRepo->findMostFrequents(VisitorService::MOST_FREQUENT_KEYWORDS_LIMIT);
        // dump($keywords);exit;
        $viewPath = 'framework/packages/VisitorPackage/view/widget/AdminMostUsedKeywordsWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminMostUsedKeywordsWidget', $viewPath, [
                'container' => $this->getContainer(),
                'keywords' => $keywords['result'] ? : []
                // 'chart2' => $this->getRenderedChart2View(-1)
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    // public function adminSiteStatisticsChart2WidgetAction()
    // {
    //     $periodStartIndex = $this->getContainer()->getRequest()->get('periodStartIndex');
    //     $response = [
    //         'view' => $this->getRenderedChartView($periodStartIndex ? (int)$periodStartIndex : -1),
    //         'data' => []
    //     ];
    //     return $this->widgetResponse($response);
    // }

    /**
    * Route: [name: admin_visitChart_widget, paramChain: /admin/visitChart/widget]
    */
    // public function adminVisitChartWidgetAction()
    // {
    //     $viewPath = 'framework/packages/VisitorPackage/view/widget/AdminVisitChartWidget/widget.php';

    //     $response = [
    //         'view' => $this->renderWidget('AdminVisitChartWidget', $viewPath, [
    //             'container' => $this->getContainer(),
    //         ]),
    //         'data' => []
    //     ];

    //     // dump($response);exit;
    //     return $this->widgetResponse($response);
    // }
}
