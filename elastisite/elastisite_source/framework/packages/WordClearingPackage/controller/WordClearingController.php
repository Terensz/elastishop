<?php
namespace framework\packages\WordClearingPackage\controller;

use framework\component\parent\PageController;

class WordClearingController extends PageController
{
    public function defaultAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }
}
