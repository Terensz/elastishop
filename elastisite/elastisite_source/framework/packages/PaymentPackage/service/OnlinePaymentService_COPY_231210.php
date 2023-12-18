<?php
namespace framework\packages\PaymentPackage\service;

use App;
use framework\kernel\utility\BasicUtils;
use framework\component\parent\Service;
use framework\kernel\base\Container;
use framework\kernel\utility\FileHandler;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\PaymentPackage\repository\PaymentRepository;
// use framework\packages\PaymentPackage\entity\OnlineGateway;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\service\WebshopService;

/*
1.: OnlinePaymentService:
- 
*/
class OnlinePaymentService_COPY_231210 extends Service
{
    public $gatewayProviderName;

    // public $gateway;
    public $transaction;

    public $communication;

    public $gatewayOperator;

    public static $gatewayProviderNameCache;

    public static $activeGatewayProviderNameCache;

    public static $configCache = [];

    /**
     * Instance methods
    */

    public function __construct(string $gatewayProviderName, Shipment $shipment)
    {
        $this->gatewayProviderName = $gatewayProviderName;
        // $this->wireService('PaymentPackage/entity/OnlineGateway');
        $this->wireService('PaymentPackage/service/PaymentTransaction');
        $this->wireService('PaymentPackage/service/parent/OnlineGatewayOperator');
        // $this->setService('PaymentPackage/service/ChallengeFlow');
        $this->transaction = new PaymentTransaction();
        $this->loadGatewayConfig($this->gatewayProviderName);
        $this->loadGatewayOperator($shipment);
    }

    public function loadGatewayConfig($gatewayProviderName)
    {
        if (!$gatewayProviderName) {
            dump($this);exit;
        }
        $config = $this->getGatewayConfig($gatewayProviderName);
        // dump($config);exit;
        foreach ($config as $key => $value) {
            $this->transaction->__set($key, $value);
        }
    }

    public function loadGatewayOperator($shipment)
    {
        $this->setService('PaymentPackage/gatewayProviders/'.$this->gatewayProviderName.'/GatewayOperator');
        $this->gatewayOperator = $this->getService('GatewayOperator');
        $this->gatewayOperator->shipment = $shipment;
        $this->gatewayOperator->paymentService = $this;
        $this->gatewayOperator->init();
    }

    public function preparePayment()
    {
        return $this->gatewayOperator->preparePayment();
    }

    /**
     * Static methods
    */

    /**
     * Searches the succesful payment, bound to that shipment.
    */
    public static function getPayment($shipment, $paymentCode = null) : ? Payment
    {
        $container = App::getContainer();
        $container->wireService('PaymentPackage/repository/PaymentRepository');

        $paymentRepo = new PaymentRepository();

        $conditions = [
            ['key' => 'shipment_id', 'value' => $shipment->getId()],
            ['key' => 'closed_at', 'value' => '*null*']
        ];
        if ($paymentCode) {
            $conditions[] = ['key' => 'payment_code', 'value' => $paymentCode];
        }

        $result = $paymentRepo->findOneBy(['conditions' => $conditions]);
        // dump($conditions);
        // dump($result);exit;

        return $result;
    }

    // public static function storePayment($payment)
    // {
    //     return $payment->getRepository()->store($payment);
    // }

    public static function getPaymentParams($shipment, $paymentMethod = null)
    {
        // $container = App::getContainer();
        // dump('=====getPaymentParams');
        self::refreshPaymentStatus($shipment, $paymentMethod);

        $result = [];
        $result['paymentId'] = null;
        $result['gatewayProvider'] = null;
        $result['gatewayUrl'] = null;
        $result['paymentCode'] = null;
        $result['ipnCalled'] = null;
        $result['status'] = null;
        $payment = self::getPayment($shipment);

        if ($payment) {
            $result['paymentId'] = $payment->getId();
            $result['gatewayProvider'] = $payment->getGatewayProvider();
            $result['gatewayUrl'] = $payment->getGatewayUrl();
            $result['paymentCode'] = $payment->getPaymentCode();
            $result['ipnCalled'] = $payment->getIpnCalled();
            $result['status'] = $payment->getStatus();
        }
        // dump($result);exit;

        return $result;
    }

    public static function refreshPaymentStatus($shipment, $paymentMethod = null)
    {
        $payment = self::getPayment($shipment);
        if ($payment) {
            $gatewayProviderName = $payment->getGatewayProvider();
            $paymentService = new self($gatewayProviderName, $shipment);
            $gatewayOperator = $paymentService->gatewayOperator;
            $gatewayOperator->refreshPaymentStatus();
        } else {
            if ($paymentMethod) {
                $paymentService = new self($paymentMethod, $shipment);
                $paymentService->preparePayment();
            }
        }
    }

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
            $gatewayConfig = self::getGatewayConfig($gatewayProviderName, 'prod');
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
}