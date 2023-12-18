<?php
namespace framework\packages\SchedulePackage\controller;

use framework\component\parent\PageController;

class ScheduleController extends PageController
{
    /**
    * Route: [name: admin/events, paramChain: /admin/events]
    */
    public function adminEventsAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }
}
