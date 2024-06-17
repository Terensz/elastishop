<?php
namespace framework\packages\AiPackage\controller;

use framework\component\parent\PageController;
// use framework\packages\ArticlePackage\entity\Article;

class AiController extends PageController
{
    /**
    * Route: [name: ai, paramChain: /ai]
    */
    public function aiViewAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }
}
