<?php
namespace framework\packages\DevPackage\controller;

use framework\component\parent\PageController;

class DownListenerController extends PageController
{
    /**
    * Route: [name: down_listener, paramChain: /admin/downListener]
    */
    public function downListenerAction()
    {
        // dump('alma');
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }
}
