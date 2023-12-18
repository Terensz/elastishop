<?php 

namespace framework\packages\WebshopPackage\service\payment\SimplePay;

use framework\kernel\component\Kernel;
use framework\packages\WebshopPackage\service\payment\SimplePay\src\SimplePayStart;
use framework\packages\WebshopPackage\service\payment\SimplePay\src\SimplePayQuery;
use framework\packages\WebshopPackage\service\payment\SimplePay\src\SimplePayFinish;
use framework\packages\WebshopPackage\service\payment\SimplePay\src\SimplePayRefund;
use framework\packages\WebshopPackage\service\payment\SimplePay\entity\SimplePayMerchant;

/**
 * tech contact: itsupport@otpmobil.com 
 * orderRef - the reference of THIS webshop
 * transactionId - the SimplePay transaction ID
 * timeout - the time limit until the transaction can be done
*/
class TransactionHandler extends Kernel
{
    private $orderRef;
    private $transactionId;
    private $total;
    private $originalTotal;
    private $approveTotal;
    private $refundTotal;
    private $merchant;
    private $transactionObject;

    public function __construct()
    {
        $this->getContainer()->wireService('WebshopPackage/service/payment/SimplePay/src/Communication');
        $this->getContainer()->wireService('WebshopPackage/service/payment/SimplePay/src/Logger');
        $this->getContainer()->wireService('WebshopPackage/service/payment/SimplePay/src/Sca');
        $this->getContainer()->wireService('WebshopPackage/service/payment/SimplePay/src/Signature');
        $this->getContainer()->wireService('WebshopPackage/service/payment/SimplePay/src/Views');
        $this->getContainer()->wireService('WebshopPackage/service/payment/SimplePay/src/Base');
        $this->getContainer()->wireService('WebshopPackage/service/payment/SimplePay/src/SimplePayBack');
        $this->getContainer()->wireService('WebshopPackage/service/payment/SimplePay/src/SimplePayFinish');
        $this->getContainer()->wireService('WebshopPackage/service/payment/SimplePay/src/SimplePayIpn');
        $this->getContainer()->wireService('WebshopPackage/service/payment/SimplePay/src/SimplePayQuery');
        $this->getContainer()->wireService('WebshopPackage/service/payment/SimplePay/src/SimplePayRefund');
        $this->getContainer()->wireService('WebshopPackage/service/payment/SimplePay/src/SimplePayStart');
        $this->autoSetMerchant();
    }

    public function autoSetMerchant()
    {
        $this->getContainer()->wireService('WebshopPackage/service/payment/SimplePay/entity/SimplePayMerchant');
        $merchant = new SimplePayMerchant();
        $merchant->setMerchantId('M0012087A');
        $merchant->setSecretKey('ZmenDuo8312KK32w082KT7VwXYr8Sk2u'); 
        $merchant->setName('Papp Ferenc');
        $merchant->setCurrency('HUF');
        $this->merchant = $merchant;
    }  

    public function setOrderRef($orderRef)
    {
        $this->orderRef = $orderRef;
    }

    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function setOriginalTotal($originalTotal)
    {
        $this->originalTotal = $originalTotal;
    }

    public function setApproveTotal($approveTotal)
    {
        $this->approveTotal = $approveTotal;
    }

    public function setRefundTotal($refundTotal)
    {
        $this->refundTotal = $refundTotal;
    }

    public function createTransaction($type)
    {
        // if ($type == 'refund') {
        //     $this->createRefund();
        // }
        $methodName = $type.'Transaction';
        $this->$methodName();
    }

    public function addConfigToTransaction()
    {
        $this->transactionObject->addConfig(array(
            'HUF_MERCHANT' => $this->merchant->getMerchantId(),
            'HUF_SECRET_KEY' => $this->merchant->getSecretKey(),
            'URL' => $this->getContainer()->getUrl()->getHttpDomain().'/payment/back',
            'SANDBOX' => true,
            'LOGGER' => false,
            // 'LOG_PATH' => 'log',
            'AUTOCHALLENGE' => true,
            // 'GET_DATA' => [
            //     'r' => '',
            //     's' => '',
            // ],
            'GET_DATA' => [],
            'POST_DATA' => $this->getContainer()->getRequest()->getAll(),
            'SERVER_DATA' => $_SERVER
        ));
    }

    public function startTransaction()
    {
        $this->transactionObject = new SimplePayStart();
        $this->transactionObject->addData('orderRef', $this->orderRef);
        $this->transactionObject->addData('currency', $this->merchant->getCurrency());
        $this->addConfigToTransaction();
        $this->transactionObject->addData('total', $this->total);
        $this->transactionObject->addData('threeDSReqAuthMethod', '02');
        $this->transactionObject->addData('customerEmail', 'sdk_test@otpmobil.com');
        $this->transactionObject->addData('language', 'HU');
        $timeoutInSec = 600;
        $timeout = @date("c", time() + $timeoutInSec);
        $this->transactionObject->addData('timeout', $timeout);
        $this->transactionObject->addData('methods', array('CARD'));
        // dump($this->transactionObject->config);
        $this->transactionObject->addData('url', $this->transactionObject->config['URL']);
        $this->transactionObject->addGroupData('invoice', 'name', 'SimplePay V2 Tester');
        $this->transactionObject->addGroupData('invoice', 'country', 'hu');
        $this->transactionObject->addGroupData('invoice', 'state', 'Budapest');
        $this->transactionObject->addGroupData('invoice', 'city', 'Budapest');
        $this->transactionObject->addGroupData('invoice', 'zip', '1111');
        $this->transactionObject->addGroupData('invoice', 'address', 'Address 1');
        $this->transactionObject->formDetails['element'] = 'button';
        $this->transactionObject->runStart();
        $this->transactionObject->getHtmlForm();
        $result = $this->transactionObject->returnData['form'];

        dump($result);exit;
    }

    public function scaTransaction()
    {
        $this->transactionObject = new SimplePayQuery();
        $this->addConfigToTransaction();
        $this->transactionObject->addData('orderRef', $this->orderRef);
        $this->transactionObject->addData('transactionId', $this->transactionId);
        $this->transactionObject->addConfigData('merchantAccount', $this->merchant->getMerchantId());
        $this->transactionObject->runQuery();
    }

    public function finishTransaction()
    {
        $this->transactionObject = new SimplePayFinish();
        $this->transactionObject->addData('orderRef', $this->orderRef);
        $this->transactionObject->addData('transactionId', $this->transactionId);
        $this->transactionObject->addData('currency', $this->merchant->getCurrency());
        $this->addConfigToTransaction();
        $this->transactionObject->addConfigData('merchantAccount', $this->merchant->getMerchantId());
        $this->transactionObject->addData('originalTotal', $this->originalTotal);
        $this->transactionObject->addData('approveTotal', $this->approveTotal);
        $this->transactionObject->runFinish();
    }

    public function refundTransaction()
    {
        $this->transactionObject = new SimplePayRefund();
        $this->transactionObject->addData('orderRef', $this->orderRef);
        $this->transactionObject->addData('transactionId', $this->transactionId);
        $this->transactionObject->addData('currency', $this->merchant->getCurrency());
        $this->addConfigToTransaction();
        $this->transactionObject->addConfigData('merchantAccount', $this->merchant->getMerchantId());
        $this->transactionObject->addData('refundTotal', $this->refundTotal);
        $this->transactionObject->runRefund();
    }

    public function queryTransaction()
    {
        $this->transactionObject = new SimplePayQuery();
        $this->addConfigToTransaction();
        $this->transactionObject->addData('orderRef', $this->orderRef);
        $this->transactionObject->addData('transactionId', $this->transactionId);
        $this->transactionObject->addConfigData('merchantAccount', $this->merchant->getMerchantId());
        $this->transactionObject->runQuery();
    }
}