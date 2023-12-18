<?php
namespace framework\packages\ElastiSitePackage\controller;

use framework\component\parent\WidgetController;

class ESDocumentationWidgetController extends WidgetController
{
    const MENU_SYSTEM = array(
        array(
            'routeNames' => array('elastisite_documentation_index', 'elastisite_documentation_how-to-start'),
            'groupTitle' => 'elastisite.documentation',
            'items' => array(
                array(
                    'routeName' => 'elastisite_documentation_index',
                    'title' => 'prologue'
                ),
                array(
                    'routeName' => 'elastisite_documentation_how-to-start',
                    'title' => 'how.to.start'
                ),
                
            )
        ),
    );
    /**
    * Route: [name: widget_BannerWidget, paramChain: /widget/BannerWidget]
    */
    public function bannerWidgetAction()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/BannerWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('BannerWidget', $viewPath, [
                'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: elastisite_ESDocumentationWidget, paramChain: /elastisite/ESDocumentationWidget]
    */
    public function eSDocumentationWidgetAction()
    {
        $viewPath = 'framework/packages/ElastiSitePackage/view/widget/ESContentWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('ESContentWidget', $viewPath, [
                'container' => $this->getContainer(),
                'file' => $this->getContainer()->getRouting()->getPageRoute()->getName().'_'.$this->getSession()->getLocale(),
                'documentTitle' => '',
                'message' => ''
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponseWithWordExplanation($response);
    }

    /**
    * Route: [name: ESDocumentationSubmenuWidget, paramChain: /ESDocumentationSubmenuWidget]
    */
    public function eSDocumentationSubmenuWidgetAction()
    {
        $viewPath = 'framework/packages/ElastiSitePackage/view/widget/ESDocumentationSubmenuWidget/widget.php';
// dump($this->getContainer()->getRouting()->getPageRoute()->getName());exit;
        $view = $this->renderWidget('ESDocumentationSubmenuWidget', $viewPath, [
            'container' => $this->getContainer(),
            // 'file' => $this->getContainer()->getRouting()->getPageRoute()->getName().'_'.$this->getSession()->getLocale(),
            'title' => trans($this->getContainer()->getRouting()->getPageRoute()->getTitle()),
            'routeName' => $this->getContainer()->getRouting()->getPageRoute()->getName(),
            'menuSystem' => self::MENU_SYSTEM
        ]);
        $response = [
            'view' => $view,
            'data' => []
        ];


        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: ESDocumentationContentWidget, paramChain: /ESDocumentationContentWidget]
    */
    public function eSDocumentationContentWidgetAction()
    {
        $viewPath = 'framework/packages/ElastiSitePackage/view/widget/ESDocumentationContentWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('ESContentWidget', $viewPath, [
                'container' => $this->getContainer(),
                'file' => $this->getContainer()->getRouting()->getPageRoute()->getName().'_'.$this->getSession()->getLocale(),
                'documentTitle' => '',
                'message' => ''
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponseWithWordExplanation($response);
    }
}
