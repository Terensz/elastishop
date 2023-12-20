<?php
namespace framework\packages\PaymentPackage\gatewayProviders\Barion;

use App;
use framework\component\helper\StringHelper;
use framework\kernel\utility\BasicUtils;
use framework\component\parent\Service;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\PaymentPackage\service\OnlinePaymentService;
use framework\packages\PaymentPackage\service\parent\OnlineGatewayOperator;
use framework\packages\ToolPackage\service\CurlApiCaller;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\repository\ShipmentRepository;
use framework\packages\WebshopPackage\service\ShipmentService;

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

class GatewayOperator extends OnlineGatewayOperator
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
        'Canceled' => Payment::PAYMENT_STATUS_CANCELLED,
        'Succeeded' => Payment::PAYMENT_STATUS_SUCCEEDED,
        'Failed' => Payment::PAYMENT_STATUS_FAILED,
        'PartiallySucceeded' => Payment::PAYMENT_STATUS_PARTIALLY_SUCCEEDED,
        'Expired' => Payment::PAYMENT_STATUS_EXPIRED,
    ];

    public OnlinePaymentService $onlinePaymentService;

    // public $payment;

    // public $packDataSet;

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

    // public function saveAndRefreshPaymentEntity()
    // {
    //     $this->onlinePaymentService->paymentEntity = $this->onlinePaymentService->paymentRepository->store($this->onlinePaymentService->paymentEntity);
    //     App::getContainer()->wireService('WebshopPackage/service/ShipmentService');
    //     App::getContainer()->wireService('WebshopPackage/repository/ShipmentRepository');
    //     $collection = ShipmentRepository::getShipmentCollectionFromId($this->onlinePaymentService->packDataSet['pack']['id']);
    //     $this->onlinePaymentService->packDataSet = ShipmentService::assembleShipmentDataSet($collection, true);
    // }

    public function init()
    {
        // if (!isset($this->onlinePaymentService->packDataSet['pack'])) {
        //     dump($this->onlinePaymentService->packDataSet);
        // }
        $this->onlinePaymentService->paymentTransaction->shipmentCode = $this->onlinePaymentService->packDataSet['pack']['code'];
        // $this->payment = $this->findPayment($this->shipment);
        // dump($this->onlinePaymentService);
        $this->onlinePaymentService->paymentEntity->setGatewayProvider(self::GATEWAY_PROVIDER);
        $this->onlinePaymentService->paymentEntity->setPaymentMethod(self::PAYMENT_METHOD);
        $this->onlinePaymentService->saveAndRefreshPaymentEntity();
        return parent::init();
    }

    public function getAndSavePaymentStatus($forceSavePayment = false)
    {
        // dump('===refreshPaymentStatus');
        $paymentStatus = $this->getPaymentStatus();

        if ($forceSavePayment || ($paymentStatus && isset($paymentStatus['Status']) && !empty($paymentStatus['Status']))) {
            // dump($paymentStatus);
            // dump($this->onlinePaymentService->paymentEntity);
            $this->onlinePaymentService->paymentEntity->setStatus($paymentStatus['Status']);
            // if (in_array($paymentStatus->Status, Payment::CLOSABLE_PAYMENT_STATUSES) && !$this->payment->getClosedAt()) {
            //     $this->payment->setClosedAt(new \DateTime());
            // }
            $this->onlinePaymentService->saveAndRefreshPaymentEntity();
        }

        return $paymentStatus;

        // dump($paymentStatus);exit;
    }

    public function getPaymentStatus()
    {
        if (!$this->onlinePaymentService->paymentEntity->getPaymentCode()) {
            // dump($this->onlinePaymentService->paymentEntity);
            return false;
            throw new \Exception('missing payment code');
        }

        $this->wireService('ToolPackage/service/CurlApiCaller');
        $curlApiCaller = new CurlApiCaller();
        $curlApiCaller->method = 'get';
        $curlApiCaller->data = [
            'POSKey' => $this->onlinePaymentService->paymentTransaction->POSKey,
            'PaymentId' => $this->onlinePaymentService->paymentEntity->getPaymentCode()
        ];

        $curlApiCaller->call($this->onlinePaymentService->paymentTransaction->APIUrlBase . $this->onlinePaymentService->paymentTransaction->getPaymentStateRoute);

        $response = $curlApiCaller->output;

        if (!empty($response['Errors'])) {
            // dump($curlApiCaller);exit;
            $this->errors = $response['Errors'];
        }

        $this->onlinePaymentService->providerApiResponse = $response;
        // return $curlApiCaller->output;
        return $response;
        // dump($curlApiCaller->output);exit;
        // API answer: "The requested resource does not support http method 'POST'."
    }

    /**
     * @todo ezt erdemes kiszervezni majd, mert jo lesz barmire.
     * 
    */
    // public static function processPaymentResponse(array $response)
    // {
    //     $pattern = self::getPreparePaymentResponsePattern();
    //     $processedResponse = $pattern; // A minta alapján inicializáljuk a feldolgozott választ
    
    //     foreach ($response as $key => $value) {
    //         if (isset($processedResponse[$key])) {
    //             if (is_array($value) && is_array($processedResponse[$key])) {
    //                 // Rekurzív hívás, ha mindkét érték tömb
    //                 $processedResponse[$key] = self::processPaymentResponse($value);
    //             } else {
    //                 // Az érték másolása, ha nem tömb
    //                 $processedResponse[$key] = $value;
    //             }
    //         }
    //     }
    
    //     return $processedResponse;
    // }
    public static function processPaymentResponse(array $response)
    {
        $pattern = self::getPreparePaymentResponsePattern();

        foreach ($response as $key => $value) {
            if (isset($pattern[$key])) {
                if (is_array($value) && is_array($pattern[$key])) {
                    $response[$key] = self::processPaymentResponse($value);
                } else {
                    $response[$key] = $value;
                }
            }
        }

        return $response;
    }

    /**
     * 
    */
    public function preparePayment()
    {
        // dump('GatewayOperator/preparePayment()');
        $this->getPaymentStatus();

        $this->wireService('ToolPackage/service/CurlApiCaller');
        $curlApiCaller = new CurlApiCaller();

        // dump($this->onlinePaymentService->paymentEntity->getId());
        // dump($this->onlinePaymentService->paymentEntity->getStatus());
        // dump(in_array($this->onlinePaymentService->paymentEntity->getStatus(), [Payment::PAYMENT_STATUS_CREATED, Payment::PAYMENT_STATUS_AUTHORIZED, Payment::PAYMENT_STATUS_WAITING]));

        // dump($this->payment);exit;
        if ($this->onlinePaymentService->paymentEntity->getId() && (in_array($this->onlinePaymentService->paymentEntity->getStatus(), [Payment::PAYMENT_STATUS_COLLECTION_CAN_BE_PREPARED]))) {
            // dump($this->onlinePaymentService->paymentEntity->getId());
            // dump($this->onlinePaymentService->paymentEntity->getStatus());exit;
            return false;
        }

        $curlApiCaller->data = $this->getPreparePaymentData();
        // $curlApiCaller->addHeaderElement('PosKey', $this->paymentService->transaction->POSKey);
        // dump($this->paymentService->transaction);exit;
        $curlApiCaller->call($this->onlinePaymentService->paymentTransaction->APIUrlBase . $this->onlinePaymentService->paymentTransaction->paymentPrepareRoute);
        // $this->payment->setShipment($this->shipment);
        // dump($this->onlinePaymentService->paymentEntity);
        $response = self::processPaymentResponse($curlApiCaller->output);

        if (!empty($response['Errors'])) {
            // dump($curlApiCaller);exit;
            $this->errors = $response['Errors'];
        }
        // dump($curlApiCaller->output);
        // dump($response);//exit;
        $this->onlinePaymentService->paymentEntity->setPaymentCode($response['PaymentId']);
        $this->onlinePaymentService->paymentEntity->setQrUrl($response['QRUrl']);
        $status = $response['Transactions'][0]['Status'] ? : 'Invalid';
        $this->onlinePaymentService->paymentEntity->setStatus(isset(self::PAYMENT_STATUS_CONVERSION[$status]) ? self::PAYMENT_STATUS_CONVERSION[$status] : Payment::PAYMENT_STATUS_INVALID);
        $this->onlinePaymentService->paymentEntity->setGatewayUrl($response['GatewayUrl']);
        $this->onlinePaymentService->paymentEntity->setRecurrenceResult($response['RecurrenceResult']);
        $this->onlinePaymentService->paymentEntity->setThreeDsAuthClientData($response['ThreeDSAuthClientData']);
        $this->onlinePaymentService->paymentEntity->setTotalGrossValue($curlApiCaller->data['Transactions'][0]['Total']);
        $this->onlinePaymentService->paymentEntity->setCurrency($curlApiCaller->data['Currency']);
        $this->onlinePaymentService->paymentEntity->setRedirectedAt(new \DateTime());
        $this->onlinePaymentService->saveAndRefreshPaymentEntity();
        $this->onlinePaymentService->providerApiResponse = $response;

        // dump($this->onlinePaymentService->paymentEntity);exit;
        // dump($curlApiCaller);exit;
        return $curlApiCaller;
    }

    public static function getPreparePaymentResponsePattern()
    {
        return [
            'PaymentId' => null,
            'PaymentRequestId' => null,
            'Status' => null,
            'QRUrl' => null,
            'Transactions' => [
                [
                    'POSTransactionId' => null,
                    'TransactionId' => null,
                    'Status' => null,
                    'Currency' => null,
                    'TransactionTime' => null,
                    'RelatedId' => null,
                ]
            ],
            'RecurrenceResult' => null,
            'ThreeDSAuthClientData' => null,
            'GatewayUrl' => null,
            'RedirectUrl' => null,
            'CallbackUrl' => null,
            'TraceId' => null,
            'Errors' => null,
        ];
    }

    private function getPreparePaymentData()
    {
        // dump($this->shipment);
        $gatewayConfig = OnlinePaymentService::getGatewayConfig(self::GATEWAY_PROVIDER);

        // dump($gatewayConfig);exit;
        $total = 0;
        $transactionItems = [];
        // if (!isset($this->onlinePaymentService->packDataSet['pack'])) {
        //     dump($this->onlinePaymentService->packDataSet);
        // }
        foreach ($this->onlinePaymentService->packDataSet['pack']['packItems'] as $shipmentItemData) {
            // dump($shipmentItemData);
            $itemTotal = $shipmentItemData['product']['actualPrice']['grossItemPriceRounded2'];
            // $originalDescription = $shipmentItemData['product']['productDescription'];
            // $modifiedDescription = StringHelper::cutLongString(strip_tags($originalDescription), 49);
            // $modifiedDescription = empty($modifiedDescription) ? $shipmentItemData['product']['productName'] : $modifiedDescription;
            $modifiedDescription = $shipmentItemData['product']['shortInfo'] ? : $shipmentItemData['product']['name'];
            // dump('originalDescription: '.$originalDescription);
            // dump('modifiedDescription: '.$modifiedDescription);
            $transactionItems[] = [
                "Name" => $shipmentItemData['product']['name'],
                "Description" => $modifiedDescription,
                "Quantity" => $shipmentItemData['quantity'],
                "Unit" => "piece",
                "UnitPrice" => $shipmentItemData['product']['actualPrice']['grossUnitPriceRounded2'],
                "ItemTotal" => $shipmentItemData['product']['actualPrice']['grossItemPriceRounded2'],
                "SKU" => $shipmentItemData['product']['SKU']
            ];
            $total += $itemTotal;
        }
        $data = [
            //'PosKey' => $this->paymentService->transaction->POSKey,
            'POSKey' => $this->onlinePaymentService->paymentTransaction->POSKey,
            'PaymentType' => 'Immediate',
            'GuestCheckOut' => true,
            'PaymentRequestId' => $this->onlinePaymentService->paymentTransaction->shipmentCode,
            'FundingSources' => [
                'Balance',
                'BankCard',
                'BankTransfer'
            ],
            // 'FundingSources' => ['All'],
            'Currency' => $this->onlinePaymentService->paymentTransaction->currency,
            // 'RedirectUrl' => $this->getContainer()->getUrl()->getHttpDomain().'/api/service/Barion/payment/redirect/'.$this->paymentService->transaction->shipmentCode,
            'RedirectUrl' => $this->getContainer()->getUrl()->getHttpDomain().'/payment/redirectFromGatewayProvider/Barion/'.$this->onlinePaymentService->paymentTransaction->shipmentCode,
            'CallbackUrl' => $this->getContainer()->getUrl()->getHttpDomain().'/api/service/Barion/payment/ipn/'.$this->onlinePaymentService->paymentTransaction->shipmentCode,
            'Transactions' => [
                [
                    'POSTransactionId' => $this->onlinePaymentService->paymentTransaction->shipmentCode,
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

    // public static function getPaymentStatusResponsePattern()
    // {
    //     return [
    //         'XXXXXXX' => null,
    //         'XXXXXXX' => null,
    //         'XXXXXXX' => null,
    //         'XXXXXXX' => null,
    //         'XXXXXXX' => null,
    //         'XXXXXXX' => null,
    //         'XXXXXXX' => null,
    //         'XXXXXXX' => null,
    //         'XXXXXXX' => null,
    //         'XXXXXXX' => null,
    //         'XXXXXXX' => null,
    //         'XXXXXXX' => null,
    //         'XXXXXXX' => [
    //             'XXXXXXX' => [
    //                 'XXXXXXX' => null,
    //                 'XXXXXXX' => null,
    //                 'XXXXXXX' => null,
    //                 'XXXXXXX' => null,
    //             ],
    //             'XXXXXXX' => null,
    //             'XXXXXXX' => null,
    //         ],
    //         'XXXXXXX' => [
    //             'XXXXXXX',
    //             'XXXXXXX',
    //             'XXXXXXX',
    //         ],
    //         'GuestCheckout' => null,
    //         'XXXXXXX' => null,
    //         'XXXXXXX' => null,
    //         'XXXXXXX' => null,
    //         'XXXXXXX' => null,
    //         'XXXXXXX' => null,
    //         'Transactions' => [
    //             [
    //                 'TransactionId' => null,
    //                 'POSTransactionId' => null,
    //                 'TransactionTime' => null,
    //                 'Total' => null,
    //                 'Currency' => null,
    //                 'RelatedId' => null,
    //                 'Status' => null,
    //             ]
    //         ],
    //     ];
    // }

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