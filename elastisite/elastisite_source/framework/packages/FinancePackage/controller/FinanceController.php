<?php
namespace framework\packages\FinancePackage\controller;

use App;
use framework\component\parent\PageController;
use framework\packages\FinancePackage\service\InvoiceService;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\service\WebshopService;

class FinanceController extends PageController
{
    public function basicAdminAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    public function basicAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer()
            // 'skinName' => 'Basic'
        ]);
    }

    /**
    * Route: [name: admin_finance_downloadInvoice, paramChain: /admin/finance/downloadInvoice/{invoiceHeaderId}]
    */
    public function adminFinanceDownloadInvoiceAction($invoiceHeaderId)
    {
        $this->getContainer()->wireService('FinancePackage/service/InvoiceService');
        $invoiceView = InvoiceService::createInvoiceView($invoiceHeaderId);

        $this->wireService('FinancePackage/service/InvoiceService');


        // $alma = InvoiceService::getVatDeclarationData($invoiceHeaderId);
        // dump($alma);exit;


        // $invoiceCreator = InvoiceService::createAndReportCreditNote($invoiceHeaderId);
        // dump($invoiceCreator);exit;




        // dump($invoiceView);
        // dump($invoiceView);exit;

        App::getContainer()->wireService('FinancePackage/service/InvoiceService');
        $invoiceHeader = InvoiceService::getInvoiceHeader($invoiceHeaderId);

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($invoiceView);
        $mpdf->Output($invoiceHeader->getInvoiceNumber().'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    }
}
