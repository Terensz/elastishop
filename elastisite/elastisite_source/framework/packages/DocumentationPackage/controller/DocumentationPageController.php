<?php
namespace framework\packages\DocumentationPackage\controller;

use framework\component\parent\PageController;

class DocumentationPageController extends PageController
{
    public function standardAction()
    {
        // dump($this);exit;
        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }
}
