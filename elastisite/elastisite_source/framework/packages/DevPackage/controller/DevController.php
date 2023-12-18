<?php
namespace framework\packages\DevPackage\controller;

use framework\component\parent\PageController;
use framework\packages\ArticlePackage\entity\Article;

class DevController extends PageController
{
    /**
    * Route: [name: dev, paramChain: /dev]
    */
    public function devAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }
}
