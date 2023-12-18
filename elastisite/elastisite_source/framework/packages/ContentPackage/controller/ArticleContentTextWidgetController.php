<?php
namespace framework\packages\ContentPackage\controller;

use framework\component\parent\WidgetController;
use framework\kernel\EntityManager\EntityChecker;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\packages\ToolPackage\repository\FileRepository;
use framework\packages\ToolPackage\entity\Grid;
use framework\packages\ToolPackage\service\Grid\GridFactory;

class ArticleContentTextWidgetController extends WidgetController
{
    public function getPrefabArticleService()
    {
        $this->setService('ContentPackage/service/PrefabArticleService');
        return $this->getService('PrefabArticleService');
    }

    /**
    * Route: [name: ArticleContentWidget}, paramChain: /ArticleContentWidget]
    */
    public function articleContentWidgetAction()
    {
        $viewPath = 'framework/packages/ContentPackage/view/widget/ArticleContentWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('ArticleContentWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    // private function completeSecondaryArray($primaryArray, $secondaryArray)
    // {
    //     // dump($primaryArray);
    //     $mendedSecondaryArray = [];
    //     foreach ($primaryArray as $primaryKey => $primaryValue) {
    //         if (!isset($secondaryArray[$primaryKey])) {
    //             $mendedSecondaryArray[$primaryKey] = null;
    //         }
    //     }

    //     return $mendedSecondaryArray;
    // }

    /**
    * Route: [name: admin_articleContents_widget}, paramChain: /admin/articleContents/widget]
    */
    // public function adminArticleContentsWidgetAction()
    // {
    //     $viewPath = 'framework/packages/ContentPackage/view/widget/AdminArticleContentsWidget/widget.php';
    //     $response = [
    //         'view' => $this->renderWidget('AdminArticleContentsWidget', $viewPath, [
    //             'container' => $this->getContainer()
    //         ]),
    //         'data' => []
    //     ];
        
    //     return $this->widgetResponse($response);
    // }
}