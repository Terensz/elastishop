<?php
namespace projects\elastishop\controller;

use App;
use framework\component\parent\WidgetController;

class CvWidgetController extends WidgetController {
    /**
    * Route: [name: cv_PappFerenc_contentWidget, paramChain: /cv/PappFerenc/contentWidget]
    */
    public function cVPappFerencContentWidgetAction()
    {
        $pageRoute = App::getContainer()->getRouting()->getPageRoute();
        $paramChainParts = explode('/', $pageRoute->getParamChain());
        $locale = 'hu';
        if (count($paramChainParts) > 1 && $paramChainParts[1] == 'en') {
            $locale = 'en';
        }
        // dump($paramChainParts);exit;

        $viewPath = 'projects/ElastiSite/view/widget/CVPappFerencContentWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('CVPappFerencContentWidget', $viewPath, [
                'locale' => $locale,
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
    * Route: [name: cv_PappFerenc_menuWidget, paramChain: /cv/PappFerenc/menuWidget]
    */
    // public function cVPappFerencMenuWidgetAction()
    // {
    //     $viewPath = 'projects/ElastiSite/view/widget/CVPappFerencMenuWidget/widget.php';

    //     $response = [
    //         'view' => $this->renderWidget('CVPappFerencMenuWidget', $viewPath, [
    //             'container' => $this->getContainer(),
    //             'documentTitle' => '',
    //             'message' => ''
    //         ]),
    //         'data' => []
    //     ];

    //     // dump($response);exit;

    //     return $this->widgetResponse($response);
    // }
}