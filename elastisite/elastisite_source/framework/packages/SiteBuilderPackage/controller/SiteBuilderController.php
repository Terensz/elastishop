<?php
namespace framework\packages\SiteBuilderPackage\controller;

use framework\component\parent\PageController;

class SiteBuilderController extends PageController
{
    public function basicAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }
}
