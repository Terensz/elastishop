<?php
namespace framework\packages\FrameworkPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\kernel\operation\OperationSupervisor;

class FrameworkWidgetController extends WidgetController
{
    /**
    * name: cp_load, paramChain: /cp/load
    */
    public function cpLoadAction()
    {
        // dump($this->getContainer()->getSession()->get('site_adminViewState'));exit;
        $oldValue = $this->getContainer()->getSession()->get('site_adminViewState');
        if ($oldValue === null) {
            $this->getContainer()->getSession()->set('site_adminViewState', false);
        }
        $projectAdminView = $this->getContainer()->getRequest()->get('viewState');
        if ($projectAdminView && in_array($projectAdminView, ['true', 'false'])) {
            if ($projectAdminView == 'true') {
                $this->getContainer()->getSession()->set('site_adminViewState', true);
            }
            if ($projectAdminView == 'false') {
                $this->getContainer()->getSession()->set('site_adminViewState', false);
            }
        }
        $newValue = $this->getContainer()->getSession()->get('site_adminViewState');
        // dump($alma);exit;

        $viewPath = 'framework/packages/FrameworkPackage/view/widget/controlPanel/widget.php';
        $response = [
            'view' => $this->renderWidget('controlPanel', $viewPath, [
                // 'container' => $this->getContainer()
                'grantedViewProjectAdminContent' => $this->getContainer()->isGranted('viewProjectAdminContent'),
                'projectAdminView' => $newValue
            ]),
            'data' => [
                'oldValue' => $oldValue,
                'newValue' => $newValue
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: cp_loadScripts, paramChain: /cp/loadScripts
    */
    public function cpLoadScriptsAction()
    {
        $scriptsFile = App::getContainer()->isGranted('viewSiteHelperContent') ? 'siteHelpersScripts' : 'visitorsScripts'; 
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/controlPanel/scripts/'.$scriptsFile.'.php';
        $response = [
            'view' => $this->renderWidget('controlPanelScripts', $viewPath, [
            ]),
            'data' => [
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: widget_SetupMenuWidget, paramChain: /widget/SetupMenuWidget
    */
    public function setupMenuWidgetAction()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/SetupMenuWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('SetupMenuWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: setup_main_widget, paramChain: /setup/MainWidget
    */
    public function setupMainWidgetAction()
    {
        App::$cache->clear();
        // dump('setupMainWidgetAction');
        // $opSup = new OperationSupervisor(true);
        $opSup = $this->getKernelObject('OperationSupervisor');
        $opSup->init(true);
        //$opSup = $this->getKernelObject('OperationSupervisor');
        // dump($opSup);exit;
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/SetupMainWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('SetupMenuWidget', $viewPath, [
                'container' => $this->getContainer(),
                'databaseConnectionErrors' => $opSup->getDatabaseConnectionErrors(),
                'missingCreateTableStatements' => $opSup->getMissingCreateTableStatements(),
                'missingTables' => $opSup->getMissingTables(),
                'databaseTableErrors' => $opSup->getDatabaseTableErrors(),
                'unwritableDynamicFiles' => $opSup->getUnwritableDynamicFiles(),
                'writablePublicDirs' => $opSup->getWritablePublicDirs(),
                'writablePublicFiles' => $opSup->getWritablePublicFiles()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: setup_createTables, paramChain: /setup/createTables
    */
    public function setupCreateMissingTablesAction()
    {
        // dump('setupCreateMissingTablesAction');
        // $opSup = new OperationSupervisor(true);
        $opSup = $this->getKernelObject('OperationSupervisor');
        $opSup->init(true);
        // dump($opSup);exit;
        $createStms = $opSup->getCreateTableStatements();
        $onCreateQueries = $opSup->getOnCreateQueries();
        $dbm = $this->getContainer()->getKernelObject('DbManager');
        $created = array();

        foreach ($createStms as $entityName => $createStm) {
            $dbm->execute($createStm);
            $created[] = $entityName;
            if (isset($onCreateQueries[$entityName])) {
                foreach ($onCreateQueries[$entityName] as $onCreateQuery) {
                    $dbm->execute($onCreateQuery);
                }
            }
        }

        App::$cache->clear();

        $viewPath = 'framework/packages/FrameworkPackage/view/widget/SetupMainWidget/createMissingTables.php';
        $response = [
            'view' => $this->renderWidget('setupCreateTables', $viewPath, [
                'container' => $this->getContainer(),
                'createdTableList' => implode(', ', $created)
                // 'opSup' => $opSup,
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: widget_Left1Widget, paramChain: /widget/Left1Widget]
    */
    public function left1WidgetAction()
    {
        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/Left1Widget/widget.php';

        $response = [
            'view' => $this->renderWidget('Left1Widget', $viewPath, [
                'container' => $this->getContainer(),
                'documentTitle' => '',
                'message' => ''
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: widget_Left2Widget, paramChain: /widget_Left2Widget]
    */
    public function left2WidgetAction()
    {
        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/Left2Widget/widget.php';

        $response = [
            'view' => $this->renderWidget('Left2Widget', $viewPath, [
                'container' => $this->getContainer(),
                'documentTitle' => '',
                'message' => ''
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: widget_MainContentWidget, paramChain: /widget/MainContentWidget]
    */
    public function mainContentWidgetAction()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/MainContentWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('MainContentWidget', $viewPath, [
                'container' => $this->getContainer(),
                'documentTitle' => '',
                'message' => ''
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: widget_MainContent2Widget, paramChain: /widget/MainContent2Widget]
    */
    public function mainContent2WidgetAction()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/MainContent2Widget/widget.php';

        $response = [
            'view' => $this->renderWidget('MainContent2Widget', $viewPath, [
                'container' => $this->getContainer(),
                'documentTitle' => '',
                'message' => ''
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * name: admin_index_widget, paramChain: /admin/index/widget
    */
    public function adminIndexWidgetAction()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminIndexWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminIndexWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: widget_ErrorWidget, paramChain: /widget/ErrorWidget
    */
    public function errorWidgetAction()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/ErrorWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('ErrorWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: widget_Error403Widget, paramChain: /widget/Error403Widget
    */
    public function error403WidgetAction()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/Error403Widget/widget.php';
        $response = [
            'view' => $this->renderWidget('ErrorWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: widget_AdminLoginMainContentWidget, paramChain: /widget/AdminLoginMainContentWidget
    */
    public function adminLoginMainContentWidgetAction()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminLoginMainContentWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminLoginMainContentWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }
}
