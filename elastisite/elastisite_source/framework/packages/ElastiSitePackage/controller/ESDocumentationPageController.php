<?php
namespace framework\packages\ElastiSitePackage\controller;

use framework\component\parent\PageController;
use framework\component\parent\JavaScript;
use framework\packages\ToolPackage\service\TextAnalist;


class ESDocumentationPageController extends PageController
{
    public function standardAction()
    {
        // dump($this);exit;
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }
}
