<?php
namespace projects\elastishop\controller;

use App;
use framework\component\parent\PageController;
use framework\packages\WebshopPackage\service\WebshopRequestService;
use framework\packages\WebshopPackage\service\WebshopService;

class BasicController extends PageController
{
    public function standardAction()
    {
        // dump(App::getContainer()->getFullRouteMap());exit;
        // $this->wireService('FinancePackage/service/InvoiceService');
        // InvoiceService::createInvoiceFromShipment(1303);
        // exit;

        // InvoiceService::

        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }

    public function homepageAction()
    {
        // dump(App::getContainer()->getFullRouteMap());exit;
        // $this->wireService('FinancePackage/service/InvoiceService');
        // InvoiceService::createInvoiceFromShipment(1303);
        // exit;

        // InvoiceService::
        // dump('Helloleo!!!!!');
        // dump(App::get());exit;

        App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');

        $webshopBase = WebshopRequestService::getSlugTransRef(WebshopService::TAG_WEBSHOP, App::getContainer()->getSession()->getLocale());

        // dump($alma);exit;

        // header('Location: /'.$webshopBase);
        // exit;

        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }
}
