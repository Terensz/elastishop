<?php
namespace framework\packages\FrameworkPackage\controller;

use framework\component\parent\PageController;
use framework\packages\ArticlePackage\entity\Article;

class BasicController extends PageController
{
    public function standardAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }
}
