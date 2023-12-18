<?php
namespace framework\packages\StaffPackage\controller;

use framework\component\parent\PageController;

class BasicController extends PageController
{
    public function standardAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }
}
