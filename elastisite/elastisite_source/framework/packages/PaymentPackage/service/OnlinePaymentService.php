<?php
namespace framework\packages\PaymentPackage\service;

use App;
use framework\kernel\utility\BasicUtils;
use framework\component\parent\Service;
use framework\kernel\base\Container;
use framework\kernel\utility\FileHandler;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\PaymentPackage\repository\PaymentRepository;
use framework\packages\WebshopPackage\dataProvider\PackDataProvider;
// use framework\packages\PaymentPackage\entity\OnlineGateway;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\repository\ShipmentRepository;
use framework\packages\WebshopPackage\service\ShipmentService;
use framework\packages\WebshopPackage\service\WebshopService;

/*
1.: OnlinePaymentService:
- 
*/
class OnlinePaymentService extends Service
{
    const LEGITIMACY_SANDBOX = 'sandbox';
    const LEGITIMACY_PRODUCTION = 'production';

    public $gatewayProviderName;

    public $paymentTransaction;

    public $paymentRepository;

    public $paymentEntity;

    // public PaymentTransaction $paymentTransaction;

    // public PaymentRepository $paymentRepository;

    // public ?Payment $paymentEntity;

    public $communication;

    public $gatewayOperator;

    public $packDataSet;

    public $providerApiResponse;

    public static $gatewayProviderNameCache;

    public static $activeGatewayProviderNameCache;

    public static $configCache = [];

    /**
     * Instance methods
    */

    public function __construct(string $gatewayProviderName, array $packDataSet, string $paymentCode = null)
    {
        App::getContainer()->wireService('PaymentPackage/repository/PaymentRepository');

        $this->packDataSet = $packDataSet;
        if (isset($packDataSet[0])) {
            dump('Nem jo!!!!!');
            dump($packDataSet);exit;
        }
        // dump($packDataSet);exit;
        $this->paymentRepository = new PaymentRepository();
        // dump($this->packDataSet['pack']['payments']);
        // dump($this->paymentRepository);
        $this->findOrCreateAndSetPayment($paymentCode);
        
        $this->gatewayProviderName = $gatewayProviderName;
        // dump($this->packDataSet);
        // $this->wireService('PaymentPackage/entity/OnlineGateway');
        $this->wireService('PaymentPackage/service/PaymentTransaction');
        $this->wireService('PaymentPackage/service/parent/OnlineGatewayOperator');
        // $this->setService('PaymentPackage/service/ChallengeFlow');
        // $this->paymentTransaction = new PaymentTransaction();
        $this->loadGatewayConfigToTransaction($this->gatewayProviderName);
        // $this->loadGatewayConfig($this->gatewayProviderName);
        $this->loadGatewayOperator();
        // dump($this);exit;
    }

    public function findOrCreateAndSetPayment($paymentCode)
    {
        // dump($this->packDataSet);exit;
        App::getContainer()->wireService('WebshopPackage/repository/ShipmentRepository');
        $activePaymentData = $this->packDataSet['pack']['payments']['active'];
        $activePaymentId = $activePaymentData ? $activePaymentData['id'] : null;

        if (!$activePaymentId) {
            $this->paymentEntity = new Payment();
            $shipmentRepository = new ShipmentRepository();
            $shipment = $shipmentRepository->find($this->packDataSet['pack']['id']);
            $this->paymentEntity->setShipment($shipment);
            $this->paymentEntity->setPaymentCode($paymentCode);
            $this->paymentEntity->setStatus(Payment::PAYMENT_STATUS_CREATED);
        } else {
            $this->paymentEntity = $this->paymentRepository->find($activePaymentId);
        }
    }

    public function saveAndRefreshPaymentEntity()
    {
        $this->savePaymentEntity();
        App::getContainer()->wireService('WebshopPackage/service/ShipmentService');
        App::getContainer()->wireService('WebshopPackage/repository/ShipmentRepository');
        App::getContainer()->wireService('WebshopPackage/dataProvider/PackDataProvider');
        $collection = ShipmentRepository::getShipmentCollectionFromId($this->packDataSet['pack']['id']);
        if (isset($collection['objectCollection'][0])) {
            $shipment = $collection['objectCollection'][0];
            $packDataSet = PackDataProvider::assembleDataSet($shipment);
        }
        $this->packDataSet = $packDataSet;
    }

    private function savePaymentEntity()
    {
        App::getContainer()->wireService('WebshopPackage/entity/Shipment');
        $this->paymentEntity = $this->paymentRepository->store($this->paymentEntity);
        if (in_array($this->packDataSet['pack']['status'], Shipment::STATUS_COLLECTION_UNPAID_STATUSES) && $this->paymentEntity->getStatus() == Payment::PAYMENT_STATUS_SUCCEEDED) {
            $shipmentRepository = new ShipmentRepository();
            $shipment = $shipmentRepository->find($this->packDataSet['pack']['id']);
            $shipment->setStatus(Shipment::SHIPMENT_STATUS_ORDER_PLACED);
            $shipmentRepository->store($shipment);
        }
    }

    public function getPaymentStatus()
    {
        return $this->gatewayOperator->getPaymentStatus();
    }

    public function getAndSavePaymentStatus($forceSavePayment = false)
    {
        return $this->gatewayOperator->getAndSavePaymentStatus($forceSavePayment);
    }

    public function loadGatewayConfigToTransaction($gatewayProviderName)
    {
        if (!$gatewayProviderName) {
            dump($this);exit;
        }
        $config = $this->getGatewayConfig($gatewayProviderName);
        // dump($config);exit;
        // foreach ($config as $key => $value) {
        //     // $this->paymentTransaction->$key = $value;
        //     $this->paymentTransaction->__set($key, $value);
        // }

        $this->paymentTransaction = new PaymentTransaction($config);
    }

    // public function loadGatewayConfig($gatewayProviderName)
    // {
    //     if (!$gatewayProviderName) {
    //         dump($this);exit;
    //     }
    //     $config = $this->getGatewayConfig($gatewayProviderName);
    //     // dump($config);exit;
    //     foreach ($config as $key => $value) {
    //         dump($key);
    //         // $this->paymentTransaction->$key = $value;
    //         $this->paymentTransaction->__set($key, $value);
    //         dump('alma2');
    //     }
    // }

    // public static function refreshPaymentStatus($shipment, $paymentMethod = null)
    // {
    //     $payment = self::getPayment($shipment);
    //     if ($payment) {
    //         $gatewayProviderName = $payment->getGatewayProvider();
    //         $paymentService = new self($gatewayProviderName, $shipment);
    //         $gatewayOperator = $paymentService->gatewayOperator;
    //         $gatewayOperator->refreshPaymentStatus();
    //     } else {
    //         if ($paymentMethod) {
    //             $paymentService = new self($paymentMethod, $shipment);
    //             $paymentService->preparePayment();
    //         }
    //     }
    // }

    public function loadGatewayOperator()
    {
        $this->setService('PaymentPackage/gatewayProviders/'.$this->gatewayProviderName.'/GatewayOperator');
        $this->gatewayOperator = $this->getService('GatewayOperator');
        // $this->gatewayOperator->packDataSet = $this->packDataSet;

        $this->gatewayOperator->onlinePaymentService = $this;
        $this->gatewayOperator->init();
    }

    public function preparePayment()
    {
        // dump($this->gatewayOperator);
        return $this->gatewayOperator->preparePayment();
    }

    /**
     * Static methods
    */

    // public static function getPaymentParams($shipment, $paymentMethod = null)
    // {
    //     // $container = App::getContainer();
    //     // dump('=====getPaymentParams');
    //     self::refreshPaymentStatus($shipment, $paymentMethod);

    //     $result = [];
    //     $result['paymentId'] = null;
    //     $result['gatewayProvider'] = null;
    //     $result['gatewayUrl'] = null;
    //     $result['paymentCode'] = null;
    //     $result['ipnCalled'] = null;
    //     $result['status'] = null;
    //     $payment = self::getPayment($shipment);

    //     if ($payment) {
    //         $result['paymentId'] = $payment->getId();
    //         $result['gatewayProvider'] = $payment->getGatewayProvider();
    //         $result['gatewayUrl'] = $payment->getGatewayUrl();
    //         $result['paymentCode'] = $payment->getPaymentCode();
    //         $result['ipnCalled'] = $payment->getIpnCalled();
    //         $result['status'] = $payment->getStatus();
    //     }
    //     // dump($result);exit;

    //     return $result;
    // }

    /**
     * This method calls the 
    */
    // public function refreshPaymentStatus(array $packDataSet, $paymentMethod = null)
    // {
    //     $paymentData = $packDataSet
    //     $payment = self::getPayment($shipment);
    //     if ($payment) {
    //         $gatewayProviderName = $payment->getGatewayProvider();
    //         $paymentService = new self($gatewayProviderName, $shipment);
    //         $gatewayOperator = $paymentService->gatewayOperator;
    //         $gatewayOperator->refreshPaymentStatus();
    //     } else {
    //         if ($paymentMethod) {
    //             $paymentService = new self($paymentMethod, $shipment);
    //             $paymentService->preparePayment();
    //         }
    //     }
    // }

    public static function getAvailableGatewayProviders()
    {
        if (self::$activeGatewayProviderNameCache) {
            return self::$activeGatewayProviderNameCache;
        }

        $availablePaymentGatewayProviders = App::getContainer()->getProjectData('availablePaymentGatewayProviders');
        if (!is_array($availablePaymentGatewayProviders)) {
            $availablePaymentGatewayProviders = null;
        }
        // dump($availablePaymentGatewayProviders);exit;

        $activeGatewayProviders = [];
        $gatewayProviderNames = self::getGatewayProviders();
        // $gatewayConfigs = [];
        foreach ($gatewayProviderNames as $gatewayProviderName) {
            // $gatewayConfigTest = self::getGatewayConfig($gatewayProviderName, 'test');
            $gatewayConfig = self::getGatewayConfig($gatewayProviderName, null);
            // dump($gatewayConfig);
            if (!$gatewayProviderName) {
                dump($gatewayProviderNames);exit;
            }
            if (isset($gatewayConfig['active']) && $gatewayConfig['active'] == 'true' && ($availablePaymentGatewayProviders === null || ($availablePaymentGatewayProviders && in_array($gatewayProviderName, $availablePaymentGatewayProviders)))) {
                $displayedName = $gatewayProviderName;
                $key = $gatewayProviderName;
                if (isset($gatewayConfig['translationReference'])) {
                    $displayedName = trans($gatewayConfig['translationReference']);
                }
                if (isset($gatewayConfig['listSequence'])) {
                    $key = $gatewayConfig['listSequence'] . ' - [listSequence]' . $gatewayProviderName;
                }
                $activeGatewayProviders[$key] = [
                    'referenceName' => $gatewayProviderName,
                    'displayedName' => $displayedName
                ];
            }
        }

        // dump('alma'); exit;

        ksort($activeGatewayProviders);

        $activeGatewayProvidersSerialArray = [];
        foreach ($activeGatewayProviders as $key => $activeGatewayProvider) {
            // if (!isset($activeGatewayProvider)) {
            //     dump($activeGatewayProviders);
            // }
            // $listSequencePos = strpos($key, '[listSequence]');
            // if ($listSequencePos !== false) {
            //     $activeGatewayProviderNameParts = explode('[listSequence]', $key);
            //     $activeGatewayProvider[''] = $activeGatewayProviderNameParts[1];
            // }
            $activeGatewayProvidersSerialArray[] = $activeGatewayProvider;
        }
        self::$activeGatewayProviderNameCache = $activeGatewayProvidersSerialArray;

        // dump($activeGatewayProviders);
        return $activeGatewayProvidersSerialArray;
    }

    public static function getGatewayProviders()
    {
        if (self::$gatewayProviderNameCache) {
            return self::$gatewayProviderNameCache;
        }
        $gatewayProviders = FileHandler::getAllDirNames('framework/packages/PaymentPackage/gatewayProviders', 'source');
        // dump($gatewayProviders);exit;

        return $gatewayProviders;
    }

    public static function getGatewayConfig($gatewayProviderName, $mode = null)
    {
        if (isset(self::$configCache[$gatewayProviderName])) {
            return self::$configCache[$gatewayProviderName];
        }
        if (empty($gatewayProviderName)) {
            dump('empty gatewayProviderName');
            // dump($this);
            exit;
        }

        $container = Container::getSelfObject();
        $container->wireService('WebshopPackage/service/WebshopService');
        $mode = $mode ? : (WebshopService::isWebshopTestMode('OnlinePaymentService::getGatewayConfig') ? 'test' : 'prod');
        $pathToFile = $container->getPathBase('config').'/sysadmin/payments/'.$mode.'/'.$gatewayProviderName.'.txt';
        $configReader = App::$configReader;
        $config = $configReader->read($pathToFile);
        self::$configCache[$gatewayProviderName] = $config;

        return $config;
    }

    public static function storePayment(Payment $payment) : Payment
    {
        return $payment->getRepository()->store($payment);
    }
}