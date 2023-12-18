<?php
namespace framework\packages\ToolPackage\controller;

use framework\component\parent\WidgetController;
use framework\packages\ToolPackage\service\Mailer;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\packages\ToolPackage\repository\FileRepository;
use framework\packages\ToolPackage\service\Uploader;
use framework\packages\WebshopPackage\service\payment\SimplePay\TransactionHandler;

class MailerWidgetController extends WidgetController
{
    /**
    * Route: [name: admin_mailerTest_widget, paramChain: /admin/mailerTest/widget]
    */
    public function adminMailerTestWidgetAction()
    {
        $this->sendMail2();
        $viewPath = 'framework/packages/ToolPackage/view/widget/AdminMailerTestWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminMailerTestWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    public function sendMail2()
    {
        $this->getContainer()->wireService('WebshopPackage/service/payment/SimplePay/TransactionHandler');

        $th = new TransactionHandler();
        $th->setOrderRef('1453');
        $th->setTotal('2530');
        $th->createTransaction('start');
    }

    public function sendMail1()
    {
        $this->wireService('ToolPackage/service/Mailer');

        $this->setService('WebshopPackage/service/WebshopService');
        $webshopService = $this->getService('WebshopService');


        $orderedProducts = [
            [
                'productName' => 'Alma',
                'quantity' => 3,
                'itemGross' => 120,
                'currency' => 'Ft'
            ],
            [
                'productName' => 'Körte',
                'quantity' => 2,
                'itemGross' => 160,
                'currency' => 'Ft'
            ]
        ];
        $mailer = new Mailer();
        $mailer->setSubject('');
        $mailer->textAssembler->setPackage('WebshopPackage');
        $mailer->textAssembler->setReferenceKey('orderSuccessfulMail');
        $mailer->textAssembler->setEmbeddedViewKeys(['orderedProducts']);
        $mailer->textAssembler->setPlaceholdersAndValues([
            'name' => trans('customer'),
            'mobile' => '+367033322222',
            'orderedProducts' => $orderedProducts,
            'totalPayable' => 1232434
        ]);
        $mailer->textAssembler->create();
        $mailer->setBody($mailer->textAssembler->getView());
        $email = 'terencecleric$gmail.com';
        $mailer->addRecipient($email, 'Papp Ferenc');
        $mailer->send();
        dump($mailer);exit;
    }

    /**
    * Route: [name: admin_mails_widget, paramChain: /admin/mails/widget]
    */
    public function adminMailsWidgetAction()
    {
        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminMailsWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminMailsWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }
}
