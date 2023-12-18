<?php
namespace framework\packages\PaymentPackage\gatewayProviders\Barion;

use framework\kernel\utility\BasicUtils;
use framework\component\parent\Service;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\PaymentPackage\service\OnlinePaymentService;
use framework\packages\PaymentPackage\service\parent\OnlineGatewayOperator;
use framework\packages\ToolPackage\service\CurlApiCaller;
use framework\packages\WebshopPackage\entity\Shipment;

/**
 * @var POSKey
 * Example: "630ee026-3e19-469f-8325-afc9bd1ae6a6"
 * @var PaymentType
 * Example: "Immediate"
 * @var PaymentRequestId 
 * Example: "EXMPLSHOP-PM-001"
*/

/*
Teszt felulet: https://test.barion.com/Registration/Individual
JSON, ami minden API-ba kell: https://docs.barion.com/Responsive_web_payment

Teszt Barion pénztárca (belépve)
https://test.barion.com/User/AccountHistory

Responsive Web Payment (Most important!!!!)
https://docs.barion.com/Responsive_web_payment

List of Barion API endpoints (Important!!!!)
https://docs.barion.com/List_of_API_endpoints

List of payment statuses
https://docs.barion.com/PaymentStatus

Sandbox: test card data
https://docs.barion.com/Sandbox

Token Payment with 3D Secure
https://docs.barion.com/Token_payment_3D_Secure

Support
https://docs.barion.com/Support

Barion technical documentation
https://docs.barion.com/Main_Page?fbclid=IwAR2vLvU278KnRnuUlKQUO0nEG6H6YDlmN6b5bchD5YyaJtfPrHGTKfXRpeY

Az Alap (Base) Barion Pixel implementációja 
https://docs.barion.com/Az-Alap-(Base)-Barion-Pixel-implementacioja

A Teljes (Full) Barion Pixel implmentációja
https://docs.barion.com/A-Teljes-(Full)-Barion-Pixel-implementacioja

Barion Pixel API referencia
https://docs.barion.com/Barion-Pixel-API-referencia

Payment callback mechanism (aka. IPN)
https://docs.barion.com/Callback_mechanism

Barion Smart Gateway (szerintem nem kell)
https://www.barion.com/hu/vallalkozasok/barion-smart-gateway/funkciok/
*/

class GatewayOperator_COPY extends OnlineGatewayOperator
{
    const GATEWAY_PROVIDER = 'Barion';

    const PAYMENT_METHOD = Payment::PAYMENT_METHOD_ONLINE_INSTANT;

    const PAYMENT_STATUS_CONVERSION = [
        'Invalid' => Payment::PAYMENT_STATUS_INVALID,
        'Prepared' => Payment::PAYMENT_STATUS_PREPARED,
        'Started' => Payment::PAYMENT_STATUS_STARTED,
        'InProgress' => Payment::PAYMENT_STATUS_IN_PROGRESS,
        'Waiting' => Payment::PAYMENT_STATUS_WAITING,
        'Reserved' => Payment::PAYMENT_STATUS_RESERVED,
        'Authorized' => Payment::PAYMENT_STATUS_AUTHORIZED,
        'Canceled' => Payment::PAYMENT_STATUS_CANCELED,
        'Succeeded' => Payment::PAYMENT_STATUS_SUCCEEDED,
        'Failed' => Payment::PAYMENT_STATUS_FAILED,
        'PartiallySucceeded' => Payment::PAYMENT_STATUS_PARTIALLY_SUCCEEDED,
        'Expired' => Payment::PAYMENT_STATUS_EXPIRED,
    ];

    public $paymentService;

    public $payment;

    public $shipment;

    public $errors = [];

    // public $gatewayOperatorRoutes = [
    //     'startPayment' => '/v2/Payment/Start',
    //     ''
    // ];

    // public $propertyToKeyConversionMap = [
    //     'APIUrl' => 'APUurl',
    //     'merchantUsername' => 'nameUser',
    //     // 'pixelId' => 'pixelId'
    // ];

    // public function authorization()
    // {
    //     return true;
    // }

    public function init()
    {
        $this->paymentService->transaction->__set('shipmentCode', $this->shipment->getCode());
        $this->payment = $this->findPayment($this->shipment);
        $this->payment->setGatewayProvider(self::GATEWAY_PROVIDER);
        $this->payment->setPaymentMethod(self::PAYMENT_METHOD);
        $this->payment = $this->payment->getRepository()->store($this->payment);
        return parent::init();
    }

    public function refreshPaymentStatus()
    {
        // dump('===refreshPaymentStatus');
        $paymentStatus = $this->getPaymentStatus();

        if ($paymentStatus && isset($paymentStatus->Status) && !empty($paymentStatus->Status)) {
            $this->payment->setStatus($paymentStatus->Status);
            // if (in_array($paymentStatus->Status, Payment::CLOSABLE_PAYMENT_STATUSES) && !$this->payment->getClosedAt()) {
            //     $this->payment->setClosedAt(new \DateTime());
            // }
            $this->payment = $this->payment->getRepository()->store($this->payment);
        }

        // dump($paymentStatus);exit;
    }

    public function getPaymentStatus()
    {
        if (!$this->payment->getPaymentCode()) {
            return false;
        }

        $this->wireService('ToolPackage/service/CurlApiCaller');
        $curlApiCaller = new CurlApiCaller();
        $curlApiCaller->method = 'get';
        $curlApiCaller->data = [
            'POSKey' => $this->paymentService->transaction->POSKey,
            'PaymentId' => $this->payment->getPaymentCode()
        ];

        // $curlApiCaller->data = [
        //     'POSKey' => 'alma',
        //     'PaymentId' => 'alma2'
        // ];

        $curlApiCaller->call($this->paymentService->transaction->APIUrlBase . $this->paymentService->transaction->getPaymentStateRoute);
        if (isset($curlApiCaller->output->Message) || (isset($curlApiCaller->output->Errors) && !empty($curlApiCaller->output->Errors))) {
            return false;
            // dump('meszidzs');
            // dump($curlApiCaller->output->Message);
        }

        return $curlApiCaller->output;

        // dump($curlApiCaller->output);exit;
        // API answer: "The requested resource does not support http method 'POST'."
    }

    public function preparePayment()
    {
        $this->getPaymentStatus();

        $this->wireService('ToolPackage/service/CurlApiCaller');
        $curlApiCaller = new CurlApiCaller();

        // dump($this->payment);

        // dump($this->payment);exit;
        if ($this->payment->getId() && (!in_array($this->payment->getStatus(), [Payment::PAYMENT_STATUS_CREATED, Payment::PAYMENT_STATUS_AUTHORIZED, Payment::PAYMENT_STATUS_WAITING]))) {
            return false;
        }



        $curlApiCaller->data = $this->getPreparePaymentData();
        // $curlApiCaller->addHeaderElement('PosKey', $this->paymentService->transaction->POSKey);
        // dump($this->paymentService->transaction);exit;
        $curlApiCaller->call($this->paymentService->transaction->APIUrlBase . $this->paymentService->transaction->paymentPrepareRoute);

        // dump($curlApiCaller);exit;
        if (isset($curlApiCaller->output->Errors) && !empty($curlApiCaller->output->Errors)) {
            dump($curlApiCaller);exit;
            $this->errors = $curlApiCaller->output->Errors;

            // return false;
        }
        // $this->payment->setShipment($this->shipment);
        $this->payment->setPaymentCode($curlApiCaller->output->PaymentId);
        $this->payment->setQrUrl($curlApiCaller->output->QRUrl);
        $status = isset($curlApiCaller->output->Transactions[0]->Status) ? $curlApiCaller->output->Transactions[0]->Status : 'Invalid';
        $this->payment->setStatus(isset(self::PAYMENT_STATUS_CONVERSION[$status]) ? self::PAYMENT_STATUS_CONVERSION[$status] : Payment::PAYMENT_STATUS_INVALID);
        $this->payment->setGatewayUrl($curlApiCaller->output->GatewayUrl);
        $this->payment->setRecurrenceResult($curlApiCaller->output->RecurrenceResult);
        $this->payment->setThreeDsAuthClientData($curlApiCaller->output->ThreeDSAuthClientData);
        $this->payment->setTotalGrossValue($curlApiCaller->data['Transactions'][0]['Total']);
        $this->payment->setCurrency($curlApiCaller->data['Currency']);
        $this->payment = $this->payment->getRepository()->store($this->payment);
        $this->shipment->addPayment($this->payment);

        // dump($this->payment);exit;
        //dump(nl2br(BasicUtils::beautifyJson(json_encode($curlApiCaller->data, JSON_PRETTY_PRINT))));
        // var_dump($curlApiCaller);
        // var_dump($this->payment);
        // var_dump($curlApiCaller);exit;
    }

    private function getPreparePaymentData()
    {
        // $this->getContainer()->setService('WebshopPackage/service/WebshopPriceService');
        // $webshopFinanceService = $this->getContainer()->getService('WebshopPriceService');
        // dump($this->shipment);
        $gatewayConfig = OnlinePaymentService::getGatewayConfig(self::GATEWAY_PROVIDER);

        // dump($gatewayConfig);exit;
        $total = 0;
        $transactionItems = [];
        foreach ($this->shipment->getShipmentItem() as $shipmentItem) {
            $itemTotal = $shipmentItem->getGrossTotalPrice(true);
            $transactionItems[] = [
                "Name" => $shipmentItem->getProduct()->getName(),
                "Description" => $shipmentItem->getProduct()->getDescription(),
                "Quantity" => $shipmentItem->getQuantity(),
                "Unit" => "piece",
                "UnitPrice" => $shipmentItem->getGrossUnitPrice(true),
                "ItemTotal" => $itemTotal,
                "SKU" => $shipmentItem->getProduct()->getSKU()
            ];
            $total += $itemTotal;
        }
        $data = [
            //'PosKey' => $this->paymentService->transaction->POSKey,
            'POSKey' => $this->paymentService->transaction->POSKey,
            'PaymentType' => 'Immediate',
            'GuestCheckOut' => true,
            'PaymentRequestId' => $this->paymentService->transaction->shipmentCode,
            'FundingSources' => [
                'Balance',
                'BankCard',
                'BankTransfer'
            ],
            // 'FundingSources' => ['All'],
            'Currency' => $this->paymentService->transaction->currency,
            // 'RedirectUrl' => $this->getContainer()->getUrl()->getHttpDomain().'/api/service/Barion/payment/redirect/'.$this->paymentService->transaction->shipmentCode,
            'RedirectUrl' => $this->getContainer()->getUrl()->getHttpDomain().'/payment/redirectFromGatewayProvider/Barion/'.$this->paymentService->transaction->shipmentCode,
            'CallbackUrl' => $this->getContainer()->getUrl()->getHttpDomain().'/api/service/Barion/payment/ipn/'.$this->paymentService->transaction->shipmentCode,
            'Transactions' => [
                [
                    'POSTransactionId' => $this->paymentService->transaction->shipmentCode,
                    'Payee' => $gatewayConfig['merchantEmail'],
                    'Total' => $total,
                    'Items' => $transactionItems,
                    // "RelatedId" => null
                ]
            ]
        ];

        // dump($data);
        // return json_encode($data);

        return $data;
    }

    /*
    {
        "POSKey": "630ee026-3e19-469f-8325-afc9bd1ae6a6",
        "PaymentType": "Immediate",
        "PaymentRequestId": "EXMPLSHOP-PM-001",
        "FundingSources": ["All"],
        "Currency": "EUR",
        "RedirectUrl": "https://merchanturl/Redirect?paymentId=xyz",
        "CallbackUrl": "https://merchanturl/Callback?paymentId=xyz",
        "Transactions": [
            {
                "POSTransactionId": "EXMPLSHOP-PM-001/TR001",
                "Payee": "webshop@example.com",
                "Total": 37.2,
                "Comment": "A brief description of the transaction",
                "Items": [
                    {
                        "Name": "iPhone 7 smart case",
                        "Description": "Durable elegant phone case / matte black",
                        "Quantity": 1,
                        "Unit": "piece",
                        "UnitPrice": 25.2,
                        "ItemTotal": 25.2,
                        "SKU": "EXMPLSHOP/SKU/PHC-01"
                    },
                    {
                        "Name": "Fast delivery",
                        "Description": "Next day delivery",
                        "Quantity": 1,
                        "Unit": "piece",
                        "UnitPrice": 12,
                        "ItemTotal": 12,
                        "SKU": "EXMPLSHOP/SKU/PHC-01"
                    }
                ]
            }
        ]
    }
    */
}