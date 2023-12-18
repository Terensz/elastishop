<?php
namespace framework\packages\PaymentPackage\service;

use framework\component\parent\TechnicalEntity;

class PaymentTransaction extends TechnicalEntity
{
    public $active;
    public $legitimacy;
    public $APIUrlBase;
    public $hashAlgorithm;
    public $logSeparator;
    public $merchantUsername;
    public $merchantEmail;
    public $merchantName;
    public $merchantPhone;
    public $merchantLocale;
    public $currency;
    public $pixelId;
    public $POSKey;
    public $publicKey;
    public $GooglePayMerchantId;
    public $paymentPrepareRoute;
    public $getPaymentStateRoute;
    public $paymentCompleteRoute;
    public $paymentFinishReservationRoute;
    public $paymentCaptureRoute;
    public $paymentCancelAuthorizationRoute;
    public $paymentRefundRoute;
    public $accountsRoute;
    public $withdrawBankTransferRoute;
    public $statementDownloadRoute;
    public $transferEmailRoute;
    public $shipmentCode;
    public $propertyToKeyConversionMap;
    // public $xxxxxxx;
    // public $xxxxxxx;
    // public $xxxxxxx;
    // public $xxxxxxx;
    // public $xxxxxxx;
    // public $xxxxxxx;
    // public $xxxxxxx;
    // public $xxxxxxx;
    // public $xxxxxxx;

    public function __construct($config)
    {
        foreach ($config as $key => $value) {
            // $this->paymentTransaction->$key = $value;
            $this->{$key} = $value;
        }
    }
    // public function __set($property, $value)
    // {
    //     $this->{$property} = $value;
    // }

    // public function __get($property)
    // {
    //     return $this->{$property};
    // }
}