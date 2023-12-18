<?php
namespace framework\packages\ContentPackage\controller;

use framework\component\parent\PageController;

class ArticleContentTextPageController extends PageController
{
    public function getPrefabArticleService()
    {
        $this->setService('ContentPackage/service/PrefabArticleService');
        return $this->getService('PrefabArticleService');
    }

    /**
    * Route: [name: articleContentText, paramChain: articleContentText, /documentum/{slug}, /document/{slug}]
    */
    public function articleContentTextAction()
    {
        // renderPage($viewData = [], $ajaxData = [], $skeletonPath = null, $title = null)
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ], [], null, 'Alma-alma');
    }
}