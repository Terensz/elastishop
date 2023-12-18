<?php
namespace projects\ASC\controller;

use App;
use framework\component\helper\DateUtils;
use framework\component\parent\PageController;
use framework\packages\FinancePackage\service\InvoiceService;
use framework\packages\FinancePackage\service\VATProfileHandler;
use projects\ASC\service\AscCalendarEventChecker;
use projects\ASC\service\AscConfigService;

class AscTimedTasksController extends PageController
{
    public function runAllAction()
    {
        App::getContainer()->wireService('projects/ASC/service/AscCalendarEventChecker');
        $alma = AscCalendarEventChecker::initCalendarEventCheck();

        dump('=========================== /timedTasks ============================');
        exit;
        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }
}
