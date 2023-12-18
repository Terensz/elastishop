<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\parent\Service;
use framework\kernel\view\ViewRenderer;
use framework\packages\LegalPackage\controller\CookieConsentService;
use framework\packages\LegalPackage\entity\VisitorConsentAcceptance;
use framework\packages\PaymentPackage\service\OnlinePaymentService;
use framework\packages\ToolPackage\service\TextAssembler;
use framework\packages\UserPackage\entity\User;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\repository\ShipmentRepository;
use framework\packages\WebshopPackage\service\ShipmentService;
use framework\packages\WebshopPackage\service\WebshopRequestService;
use framework\packages\WebshopPackage\service\WebshopService;

class WebshopResponseAssembler_ShipmentHandling extends Service
{
    private static $cache = [
        'viewParams' => null
    ];

    public static function assembleResponse($processedRequestData = null)
    {
        // dump(trans('next.step'));
        // dump(trans('please.select.payment.method'));exit;
        $viewParams = self::getShipmentHandlingParams();

        /**
         * Payment
        */
        $paymentServiceData = null;
        /**
         * We will also check the payment status.
        */
        $shipmentClosed = false;
        if ($viewParams['shipmentDataSet'] && $viewParams['errors']['Summary']['errorsCount'] == 0) {

            /**
             * We want to be sure. But it's already 99,99%.
            */
            if (isset($viewParams['shipmentDataSet'][0])) {
                /**
                 * We need the entire service, because in a case we want to check and refresh status.
                */
                $paymentService = new OnlinePaymentService('Barion', $viewParams['shipmentDataSet'][0]);
                /**
                 * We also extract data, it goes to the view.
                */
                $paymentServiceData = self::extractPaymentServiceData($paymentService);

                /**
                 * Let's check the payment for this shipment.
                */
                $paymentData = $viewParams['shipmentDataSet'][0]['shipment']['payments'];
                if ($paymentData['successful']) {
                    /**
                     * 
                    */
                } elseif ($paymentData['active']) {
                    $statusOld = $paymentData['active']['payment']['status'];
                    $paymentStatus = $paymentService->getAndSavePaymentStatus();
                    if (isset($paymentStatus['Status']) && $paymentStatus['Status'] != $statusOld) {
                        /**
                         * If meanwhile the status changed, than we should refresh the entire $viewParams.
                        */
                        $viewParams = self::getShipmentHandlingParams(true, null, true);
                        $paymentData = $viewParams['shipmentDataSet'][0]['shipment']['payments'];
                    }
                }

                /**
                 * After status refresh we may have a successful status now.
                */
                if ($paymentData['successful']) {
                    $shipmentClosed = true;
                }
            }

    
            // $paymentParams = OnlinePaymentService::getPaymentParams($shipment, 'Barion');
            // $paymentService = new OnlinePaymentService('Barion', isset($viewParams['shipmentDataSet'][0]) ? $viewParams['shipmentDataSet'][0] : null);
            // $paymentServiceData = self::extractPaymentServiceData($paymentService);
            // dump($paymentService);
            // dump($paymentServiceData);exit;
            // $paymentService->preparePayment();

            // dump('getShipmentHandlingParams');exit;
        }

        $viewParams['paymentServiceData'] = $paymentServiceData;
        $viewParams['shipmentClosed'] = $shipmentClosed;

        // dump($viewParams);exit;

        if ($viewParams['errors']['Page']['summary']['errorsCount'] > 0) {
            $viewPath = 'framework/packages/WebshopPackage/view/Sections/ShipmentHandling/Error/InvalidShipment.php';
        } else {
            $viewPath = 'framework/packages/WebshopPackage/view/Sections/ShipmentHandling/ShipmentHandling.php';
        }
        
        $view = ViewRenderer::renderWidget('WebshopPackage_ShipmentHandling', $viewPath, $viewParams);

        return [
            'view' => $view,
            'data' => [
            ]
        ];

        // $response = [
        //     'view' => $view,
        //     'data' => [
        //         // 'closeModal' => $form->isValid() ? true : false
        //     ]
        // ];

        // return WidgetResponse::create($response);
    }

    // public static function pay()
    // {
    //     $paymentParams = OnlinePaymentService::getPaymentParams($shipment, 'Barion');
    //     dump($paymentParams);
    //     dump($shipment);exit;

    //     $paymentService = new OnlinePaymentService('Barion', $shipment);
    //     $paymentService->preparePayment();
    // }

    /**
     * @var VisitorConsentAcceptance $barionThirdPartyCookieAcceptance = null
    */
    public static function getBarionThirdPartyCookieAcceptance()
    {
        App::getContainer()->wireService('LegalPackage/service/CookieConsentService');
        App::getContainer()->wireService('LegalPackage/entity/VisitorConsentAcceptance');
        $barionThirdPartyCookieAcceptance = CookieConsentService::findThirdPartyCookiesAcceptances(false, 'Barion');
        if (!$barionThirdPartyCookieAcceptance || ($barionThirdPartyCookieAcceptance && $barionThirdPartyCookieAcceptance->getAcceptance() == VisitorConsentAcceptance::ACCEPTANCE_REFUSED)) {
            return false;
        }

        return true;
    }

    public static function getPaymentServiceData($shipmentDataSet)
    {
        // dump($shipmentDataSet);
        $paymentService = new OnlinePaymentService('Barion', $shipmentDataSet);
        $paymentServiceData = self::extractPaymentServiceData($paymentService);

        return $paymentServiceData;
    }

    public static function extractPaymentServiceData(OnlinePaymentService $paymentService)
    {
        App::getContainer()->wireService('PaymentPackage/service/OnlinePaymentService');
        $gatewayProvicerName = $paymentService->gatewayProviderName;
        $paymentServiceData = [
            'gatewayProviderName' => $gatewayProvicerName,
            'legitimacy' => $paymentService->paymentTransaction->legitimacy
        ];
        $paymentServiceData['transaction'] = get_object_vars($paymentService->paymentTransaction);
        // $paymentServiceData['config'] = $paymentService::$configCache[$gatewayProvicerName];

        return $paymentServiceData;
    }

    public static function assembleBarionCookieRefusedText()
    {
        App::getContainer()->wireService('ToolPackage/service/TextAssembler');
        $textAssembler = new TextAssembler();
        // dump($this->getContentTextService($subscriber));
        // $textAssembler->setContentTextService($this->getContentTextService());
        $textAssembler->setDocumentType('entry');
        $textAssembler->setPackage('WebshopPackage');
        $textAssembler->setReferenceKey('BarionCookieWasRefused');
        $textAssembler->setPlaceholdersAndValues([
            'httpDomain' => App::getContainer()->getUrl()->getHttpDomain()
        ]);
        $textAssembler->create();
        $textView = $textAssembler->getView();

        return $textView;
        // dump($textView);exit;
    }

    public static function getShipmentHandlingParams($shipmentHandlingPage = true, Shipment $shipment = null, $emptyCache = false)
    {
        if (self::$cache['viewParams'] && !$emptyCache) {
            return self::$cache['viewParams'];
        }
        App::getContainer()->wireService('PaymentPackage/service/OnlinePaymentService');
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
        App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
        App::getContainer()->wireService('WebshopPackage/service/ShipmentService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        App::getContainer()->wireService('WebshopPackage/entity/Shipment');
        // App::getContainer()->wireService('WebshopPackage/service/ShipmentService');
        App::getContainer()->wireService('WebshopPackage/repository/ShipmentRepository');

        $shipmentRepository = new ShipmentRepository();
        $shipmentCodeRequest = null;
        if ($shipmentHandlingPage) {
            $urlObject = App::getContainer()->getUrl();
            // dump($urlObject->getDetails());exit;
            $shipmentCodeRequest = isset($urlObject->getDetails()[0]) && isset($urlObject->getDetails()[1]) && $urlObject->getDetails()[0] == 'handling' ? $urlObject->getDetails()[1] : null;
            $shipment = $shipmentRepository->findOneBy(['conditions' => [['key' => 'code', 'value' => $shipmentCodeRequest]]]);
        } elseif ($shipment) {
            /**
             * that's okay. We already have the shipment.
            */
        } else {
            throw new \Exception('One argument is required.');
        }

        $userType = App::getContainer()->getUser()->getType();

        $errors = [
            'Page' => [
                'summary' => [
                    'errorsCount' => 0,
                    'case' => 0
                ]
            ],
            'BarionCookieConsent' => [
                'messages' => [
                    'barionCookieConsentMessage' => null,
                ],
                'summary' => [
                    'errorsCount' => 0,
                ]
            ],
            'PaymentMethod' => [
                'messages' => [
                    'paymentMethodValidationMessage' => null
                ],
                'summary' => [
                    'errorsCount' => 0
                ]
            ],
            'Summary' => [
                'errorsCount' => 0
            ]
        ];

        /**
         * Set of inspections to filter invalid requests.
         * These errors go to the category "Page".
        */
        if (!$shipment) {
            $errors['Page']['summary']['case'] = 1;
            $errors['Page']['summary']['errorsCount']++;
            $errors['Summary']['errorsCount']++;
            // $viewPath = 'framework/packages/WebshopPackage/view/Sections/ShipmentHandling/Error/InvalidShipment.php';
            // return WebshopResponseAssembler::returnAlternativeView('WebshopResponseAssembler_ShipmentHandling', $viewPath, ['case' => '1']);
        }

        if ($userType == User::TYPE_GUEST) {
            // dump('heloleo');
            if ($shipment && $shipment->getUserAccount()) {
                $errors['Page']['summary']['case'] = 2;
                $errors['Page']['summary']['errorsCount']++;
                $errors['Summary']['errorsCount']++;
                // $viewPath = 'framework/packages/WebshopPackage/view/Sections/ShipmentHandling/Error/GuestNeedLogin.php';
                // return WebshopResponseAssembler::returnAlternativeView('WebshopResponseAssembler_ShipmentHandling', $viewPath, ['case' => '2']);
            }
            if ($shipment && (!$shipment->getVisitorCode() || $shipment->getVisitorCode() != App::getContainer()->getSession()->getVisitorCode())) {
                $errors['Page']['summary']['case'] = 3;
                $errors['Page']['summary']['errorsCount']++;
                $errors['Summary']['errorsCount']++;
                // dump(App::getContainer()->getSession()->getVisitorCode().' - '.$shipment->getVisitorCode());
                // $viewPath = 'framework/packages/WebshopPackage/view/Sections/ShipmentHandling/Error/InvalidShipment.php';
                // return WebshopResponseAssembler::returnAlternativeView('WebshopResponseAssembler_ShipmentHandling', $viewPath, ['case' => '3']);
            }
        } elseif ($userType == User::TYPE_USER) {
            if ($shipment && (!$shipment->getUserAccount() || $shipment->getUserAccount()->getId() != App::getContainer()->getUser()->getUserAccount()->getId())) {
                $errors['Page']['summary']['case'] = 4;
                $errors['Page']['summary']['errorsCount']++;
                $errors['Summary']['errorsCount']++;
                // $viewPath = 'framework/packages/WebshopPackage/view/Sections/ShipmentHandling/Error/InvalidShipment.php';
                // return WebshopResponseAssembler::returnAlternativeView('WebshopResponseAssembler_ShipmentHandling', $viewPath, ['case' => '4']);
            }
        } else {
            $errors['Page']['summary']['case'] = 5;
            $errors['Page']['summary']['errorsCount']++;
            $errors['Summary']['errorsCount']++;
        }

        /**
         * Checking Barion cookie consent.
         * Without this the user cannot have the Barion pixel in their browser, which prevent them of paying for the shipment.
        */
        $barionThirdPartyCookieAcceptance = WebshopResponseAssembler_ShipmentHandling::getBarionThirdPartyCookieAcceptance();
        // $barionCookieText = null;
        if (!$barionThirdPartyCookieAcceptance && $shipment && $shipment->getPaymentMethod() == 'Barion') {
            // $barionCookieText = WebshopResponseAssembler_ShipmentHandling::assembleBarionCookieRefusedText();
            $errors['BarionCookieConsent']['messages']['barionCookieConsentMessage'] = WebshopResponseAssembler_ShipmentHandling::assembleBarionCookieRefusedText();
            $errors['BarionCookieConsent']['summary']['errorsCount']++;
            $errors['Summary']['errorsCount']++;
        }
        // dump($barionCookieRefusedText);//exit;
        // dump($barionThirdPartyCookieAcceptance);exit;

        // dump(OnlinePaymentService::getAvailableGatewayProviders());exit;
        $shipmentDataSet = null;
        $selectedPaymentMethod = null;
        $paymentMethods = null;
        if ($shipment) {
            // dump($shipment);
            $collection = ShipmentRepository::getShipmentCollectionFromId($shipment->getId());
            $shipmentDataSet = ShipmentService::assembleShipmentDataSet($collection);
    
            /**
             * paymentMethod
            */
            $selectedPaymentMethod = $shipment->getPaymentMethod();
            if (!$selectedPaymentMethod) {
                $errors['PaymentMethod']['messages']['paymentMethodValidationMessage'] = trans('please.select.payment.method');
                $errors['PaymentMethod']['summary']['errorsCount']++;
                $errors['Summary']['errorsCount']++;
            }

            /**
             * paymentMethods
            */
            $paymentMethods = OnlinePaymentService::getAvailableGatewayProviders();
    
            // dump($shipmentDataSet);exit;
            // dump($shipmentDataSet);exit;
            // dump($arrangedShipmentData);exit;
        }
        // dump($shipmentDataSet);exit;
        // dump(WebshopRequestService::getBaseLink());
        // dump(WebshopRequestService::getSlugTransRef(WebshopService::TAG_WEBSHOP, App::getContainer()->getSession()->getLocale()));exit;

        $viewParams = [
            'webshopBaseLink' => '/'.WebshopRequestService::getBaseLink(),
            'shipmentDataSet' => $shipmentDataSet,
            'selectedPaymentMethod' => $selectedPaymentMethod,
            'paymentMethods' => $paymentMethods,
            // 'paymentParams' => $paymentParams,
            'errors' => $errors
            // 'productsData' => $productsData,
        ];

        self::$cache['viewParams'] = $viewParams;

        return $viewParams;
    }
}