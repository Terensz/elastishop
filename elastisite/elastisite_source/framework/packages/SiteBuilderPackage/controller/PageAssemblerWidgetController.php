<?php
namespace framework\packages\SiteBuilderPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\SiteBuilderPackage\service\PageAssemblerService;

class PageAssemblerWidgetController extends WidgetController
{
    public function __construct()
    {
        App::getContainer()->wireService('SiteBuilderPackage/service/BuiltPageService');
        App::getContainer()->wireService('SiteBuilderPackage/service/PageAssemblerService');
    }

    public function adminPageAssemblerDistantViewEditorBaseAction()
    {
        // $pageRoute = App::getContainer()->getRouting()->getPageRoute();
        // $widgetChanges = $pageRoute->getWidgetChanges();

        $builtPageId = (int)App::getContainer()->getRequest()->get('builtPageId');
        if (!$builtPageId) {
            return false;
        }

        $builtPageData = PageAssemblerService::getBuiltPageData($builtPageId);

        $viewPath = 'framework/packages/SiteBuilderPackage/view/pageAssembler/distantViewEditor/base.php';
        $response = [
            'view' => $this->renderWidget('AdminSiteBuilderSideMenuWidget', $viewPath, [
                'builtPageId' => $builtPageId,
                'builtPageData' => $builtPageData
                // 'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    public function adminPageAssemblerDistantViewEditorAddLeftPanelAction()
    {
        $builtPageId = (int)App::getContainer()->getRequest()->get('builtPageId');
        if (!$builtPageId) {
            return false;
        }

        $builtPage = PageAssemblerService::getBuiltPageService()::find($builtPageId);
        if (!$builtPage) {
            return false;
        }

        $builtPage->setNumberOfPanels(2);
        $builtPage->getRepository()->store($builtPage);

        $response = [
            'view' => '',
            'data' => [

            ]
        ];

        return $this->widgetResponse($response);
    }

    public function adminPageAssemblerDistantViewEditorRemoveLeftPanelAction()
    {
        $builtPageId = (int)App::getContainer()->getRequest()->get('builtPageId');
        if (!$builtPageId) {
            return false;
        }

        $builtPage = PageAssemblerService::getBuiltPageService()::find($builtPageId);
        if (!$builtPage) {
            return false;
        }

        $builtPage->setNumberOfPanels(1);
        $builtPage->getRepository()->store($builtPage);

        $response = [
            'view' => '',
            'data' => [

            ]
        ];

        return $this->widgetResponse($response);
    }

    public function adminPageAssemblerDistantViewEditorAddWidgetAction()
    {
        $builtPageId = (int)App::getContainer()->getRequest()->get('builtPageId');
        if (!$builtPageId) {
            return false;
        }

        $builtPage = PageAssemblerService::getBuiltPageService()::find($builtPageId);
        if (!$builtPage) {
            return false;
        }

        $position = App::getContainer()->getRequest()->get('position');
        $widgetName = App::getContainer()->getRequest()->get('widgetName');

        $builtPage = PageAssemblerService::addWidget($builtPage, $position, $widgetName);

        $response = [
            'view' => '',
            'data' => [

            ]
        ];

        return $this->widgetResponse($response);
    }

    public function adminPageAssemblerDistantViewEditorRemoveWidgetAction()
    {
        $builtPageId = (int)App::getContainer()->getRequest()->get('builtPageId');
        if (!$builtPageId) {
            return false;
        }

        $builtPage = PageAssemblerService::getBuiltPageService()::find($builtPageId);
        if (!$builtPage) {
            return false;
        }

        $position = App::getContainer()->getRequest()->get('position');
        $widgetName = App::getContainer()->getRequest()->get('widgetName');
        PageAssemblerService::removeWidget($builtPage, $position, $widgetName);
        
        $response = [
            'view' => '',
            'data' => [

            ]
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    public function adminPageAssemblerDistantViewEditorSortWidgetsAction()
    {
        $builtPageId = (int)App::getContainer()->getRequest()->get('builtPageId');
        // dump($builtPageId);exit;
        if (!$builtPageId) {
            return false;
        }

        $builtPage = PageAssemblerService::getBuiltPageService()::find($builtPageId);
        if (!$builtPage) {
            return false;
        }

        $position = App::getContainer()->getRequest()->get('position');
        $widgetNames = App::getContainer()->getRequest()->get('widgetNames');
        PageAssemblerService::sortWidgets($builtPage, $position, $widgetNames);

        $response = [
            'view' => '',
            'data' => [
                'post' => App::getContainer()->getRequest()->getAll()
            ]
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }
}
