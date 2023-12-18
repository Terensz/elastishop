<?php
namespace framework\packages\FooterPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\FooterPackage\service\FooterService;

class FooterWidgetController extends WidgetController
{
    public static $footerService;

    /**
    * Route: [name: widget_FooterWidget, paramChain: /widget/FooterWidget]
    */
    public function footerWidgetAction()
    {
        $viewPath = 'framework/packages/FooterPackage/view/widget/FooterWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('FooterWidget', $viewPath, [
                'settings' => $this->getFooterSettingsArray(),
                'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    public function getFooterService() : FooterService
    {
        App::getContainer()->wireService('FooterPackage/service/FooterService');
        if (!self::$footerService) {
            self::$footerService = new FooterService();
        }

        return self::$footerService;
    }

    public function getFooterSettingsArray()
    {
        $footerService = $this->getFooterService();
        $footerSettings = [];
        foreach ($footerService::$settings as $settingKey => $settingValue) {
            $displayed = $footerService->getDisplayedSetting($settingKey);
            $footerSettings[$settingKey] = $displayed == 'null' ? null : $displayed;
        }

        return $footerSettings;
    }

    /**
    * Route: [name: admin_AdminFooterWidget, paramChain: /admin/AdminFooterWidget]
    */
    public function adminFooterWidgetAction()
    {
        // $this->setService('FrameworkPackage/service/SettingsService');
        // $settingsService = $this->getService('SettingsService');

        $viewPath = 'framework/packages/FooterPackage/view/widget/AdminFooterWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('AdminFooterWidget', $viewPath, [
                'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_AdminFooterWidget_list, paramChain: /admin/AdminFooterWidget/list]
    */
    public function adminFooterWidgetListAction()
    {
        // $this->wireService('WebshopPackage/service/FooterService');
        // $footerSettings = $this->getFooterSettingsArray();

        $viewPath = 'framework/packages/FooterPackage/view/widget/AdminFooterWidget/list.php';

        $response = [
            'view' => $this->renderWidget('AdminFooterWidget_list', $viewPath, [
                'settings' => $this->getFooterSettingsArray(),
                'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_AdminFooterWidget_edit, paramChain: /admin/AdminFooterWidget/edit]
    */
    public function adminFooterWidgetEditAction()
    {
        // $this->wireService('WebshopPackage/service/FooterService');
        // $footerSettings = $this->getFooterSettingsArray();

        $this->setService('FrameworkPackage/service/SettingsService');
        $settingsService = $this->getService('SettingsService');

        // var_dump($this->getRequest()->getAll());exit;

        $submitted = $this->getContainer()->getRequest()->get('submitted');
        if ($submitted == 'true') {
            $settingsService->processPosts(['FooterPackage_editConfig_submit']);
        }

        $viewPath = 'framework/packages/FooterPackage/view/widget/AdminFooterWidget/modal.php';

        $response = [
            'view' => $this->renderWidget('AdminFooterWidget_list', $viewPath, [
                'settings' => $this->getFooterSettingsArray(),
                'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }
}
