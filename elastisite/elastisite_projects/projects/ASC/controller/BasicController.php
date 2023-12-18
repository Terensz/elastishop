<?php
namespace projects\ASC\controller;

use App;
use framework\component\helper\DateUtils;
use framework\component\parent\PageController;
use framework\packages\FinancePackage\service\InvoiceService;
use framework\packages\FinancePackage\service\VATProfileHandler;
use projects\ASC\service\AscConfigService;

class BasicController extends PageController
{
    public function standardAction()
    {
        App::getContainer()->wireService('projects/ASC/service/AscConfigService');
        if (!App::getContainer()->isAjax()) {
            App::getContainer()->getSession()->set('AscScaleBuilder-primarySubjectBarState', AscConfigService::PRIMARY_SUBJECTBAR_STATE_OPENED);
        }
        // App::getContainer()->wireService('projects/ASC/service/AscConfigService');
        // if (App::getContainer()->getSession()->get('AscScaleBuilder-primarySubjectBarState') == AscConfigService::PRIMARY_SUBJECTBAR_STATE_CLOSED && !$currentSubject && !$juxtaposedSubject) {
        //     App::getContainer()->getSession()->set('AscScaleBuilder-primarySubjectBarState', AscConfigService::PRIMARY_SUBJECTBAR_STATE_OPENED);
        // }
        // dump(App::getContainer()->getFullRouteMap());exit;
        // $this->wireService('FinancePackage/service/InvoiceService');
        // InvoiceService::createInvoiceFromShipment(1303);
        // exit;

        // InvoiceService::

        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }
}
