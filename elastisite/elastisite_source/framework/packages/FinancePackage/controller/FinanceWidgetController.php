<?php
namespace framework\packages\FinancePackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\DataGridPackage\service\DataGridBuilder;
use framework\packages\FinancePackage\repository\InvoiceHeaderRepository;
use framework\packages\FinancePackage\service\InvoiceService;
use framework\packages\FinancePackage\service\VATProfileHandler;

// use framework\packages\UserPackage\repositorx\TemporaryAccountRepository;
// use framework\packages\UserPackage\repositorx\TemporaryPersonRepository;
// use framework\packages\WebshopPackage\repository\ProductImageRepository;

class FinanceWidgetController extends WidgetController
{
    // public function __construct()
    // {
    //     $this->getContainer()->setService('WebshopPackage/service/WebshopService');
    //     $this->getContainer()->setService('WebshopPackage/service/PriceDataService');
    // }

    /**
    * Route: [name: admin_finance_invoicesWidget, paramChain: /admin/financeInvoicesWidget]
    */
    public function adminFinanceInvoicesWidgetAction()
    {
        $this->setService('FinancePackage/repository/InvoiceHeaderRepository');
        // $this->setService('WebshopPackage/service/WebshopService');
        // $webshopService = $this->getService('WebshopService');
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $dataGridBuilder = new DataGridBuilder('AdminFinanceInvoicesDataGrid');
        $dataGridBuilder->setDeleteDisabled(true);

        // $dataGridBuilder->setValueConversion(['status' => [
        //     '0' => trans('disabled'),
        //     '1' => trans('active')
        // ]]);

        $dataGridBuilder->setPrimaryRepository($this->getService('InvoiceHeaderRepository'));
        $dataGrid = $dataGridBuilder->getDataGrid();
        $dataGrid->setEditActionUrl($this->getUrl()->getHttpDomain().'/admin/finance/editInvoice');
        $response = $dataGrid->render();

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_finance_editInvoice, paramChain: /admin/finance/editInvoice]
    */
    public function adminFinanceEditInvoiceAction()
    {
        $this->wireService('FinancePackage/repository/InvoiceHeaderRepository');
        $this->wireService('FinancePackage/service/InvoiceService');

        $repo = new InvoiceHeaderRepository();
        $id = (int)$this->getContainer()->getRequest()->get('id');

        // dump(InvoiceService::createInvoiceView($id));exit;


        $entity = $repo->find($id);
        /**
         * Checking if user permitted this entity
        */
        if ($entity && $entity->checkCorrectWebsite() == false) {
            $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
            $securityEventHandler->addEvent('TESTING_FOREIGN_DATA', $id, 'InvoiceHeaderId');
        }

        $isCreditNote = false;
        $fullyCredited = false;
        if ($entity->getCorrectedInvoiceNumber()) {
            $isCreditNote = true;
        } else {
            $creditNote = $repo->findOneBy(['conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()],
                ['key' => 'corrected_invoice_number', 'value' => $entity->getInvoiceNumber()]
            ]]);

            if ($creditNote && $creditNote->getCurrency() == $entity->getCurrency() && ($creditNote->getTotalNet() + $entity->getTotalNet() == 0)) {
                $fullyCredited = true;
            }
        }

        // dump($this->getContainer()->getRequest()->getAll());exit;
        $viewPath = 'framework/packages/FinancePackage/view/widget/AdminFinanceInvoicesWidget/edit.php';
        $response = [
            'view' => $this->renderWidget('adminContentTextEdit', $viewPath, [
                'entity' => $entity,
                'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain(),
                'invoiceView' => InvoiceService::createInvoiceView($id),
                'isCreditNote' => $isCreditNote,
                'fullyCredited' => $fullyCredited,
                // 'form' => $form,
                // 'contentText' => $contentText,
                // 'container' => $this->getContainer(),
                // 'uniqueId' => $uniqueId
            ]),
            'data' => [
                'label' => trans('handling.invoice')
            ]
        ];
        
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_finance_createCreditNote, paramChain: /admin/finance/createCreditNote/{invoiceHeaderId}]
    */
    public function adminFinanceCreateCreditNoteAction($invoiceHeaderId)
    {
        # CREDIT NOTE keszitese!!!!!
        $this->wireService('FinancePackage/service/InvoiceService');
        $invoiceCreator = InvoiceService::createAndReportCreditNote($invoiceHeaderId);
        $commStatus = $invoiceCreator->invoiceHeader->getTaxOfficeCommStatus();
        $okCommStatus = $invoiceCreator->invoiceHeader::COMM_STATUS_OK;

        if ($commStatus == $okCommStatus) {
            $result = true;
            $message = trans('credit.note.created.successfully');
        } else {
            $result = false;
            $message = trans('credit.note.create.failed');
            /**
             * @todo levelkuldes nekem!!!!!
            */
        }
        // dump($alma);exit;
        
        // $viewPath = 'framework/packages/FinancePackage/view/widget/AdminFinanceInvoicesWidget/createCreditNote.php';
        $response = [
            'view' => '',
            'data' => [
                'result' => $result,
                'message' => $message
            ]
        ];
        
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_finance_vatDeclarationTestWidget, paramChain: /admin/finance/vatDeclarationTestWidget]
    */
    public function adminVatDeclarationTestWidgetAction()
    {
        $this->wireService('FinancePackage/service/VATProfileHandler');
        $vatProfileHandler = new VATProfileHandler(App::getContainer()->getConfig()->getProjectData('VATProfile'));
        // dump(VATProfileHandler::getArrangedConfig('NAV', 'dev'));

        $viewPath = 'framework/packages/FinancePackage/view/widget/AdminVatDeclarationTestWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminVatDeclarationTestWidget', $viewPath, [
                // 'environment' => $this->getContainer()->getEnv(),
                'vatProfileHandler' => $vatProfileHandler,
                // 'settings' => $this->getVatDeclarationSettings(false)
            ]),
            'data' => [
            ]
        ];
        
        return $this->widgetResponse($response);
    }

    public function getVatDeclarationSettings()
    {

    }

    // /**
    // * Route: [name: admin_finance_downloadInvoice, paramChain: /admin/finance/downloadInvoice]
    // */
    // public function adminFinanceDownloadInvoiceAction()
    // {
    //     $mpdf = new \Mpdf\Mpdf();
    //     $mpdf->WriteHTML('<h1>Hello world!</h1>');
    //     $mpdf->AddPage();
    //     $mpdf->WriteHTML('<h1>Hello 2 world!</h1>');
    //     $mpdf->Output('alma.pdf', \Mpdf\Output\Destination::DOWNLOAD);

    //     // // $mpdf->Output();
    //     // dump($mpdf);exit;
    //     // dump($this->getContainer()->getPathBase());exit;
    // }
}
