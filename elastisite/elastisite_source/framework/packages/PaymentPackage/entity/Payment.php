<?php
namespace framework\packages\PaymentPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\WebshopPackage\entity\Shipment;

class Payment extends DbEntity
{
    // const STATUS_CODE_CONVERSIONS = [
    //     '0' => 'disabled',
    //     '1' => 'active',
    //     '2' => 'proven'
    // ];

    const PAYMENT_METHOD_CASH = 'cash';
    const PAYMENT_METHOD_CASH_ON_DELIVERY = 'cash-on-delivery';
    const PAYMENT_METHOD_PAYMENT_UPON_RECEIPT = 'payment-upon-receipt';
    const PAYMENT_METHOD_ONLINE_INSTANT = 'online-instant';

    const PAYMENT_STATUS_INVALID = 'Invalid';
    const PAYMENT_STATUS_FAILED = 'Failed';
    const PAYMENT_STATUS_EXPIRED = 'Expired';
    const PAYMENT_STATUS_CANCELLED = 'Canceled';
    /**
     * It's very important: Created status is outside the shared statuses with Barion.
    */
    const PAYMENT_STATUS_CREATED = 'Created';
    const PAYMENT_STATUS_WAITING = 'Waiting';
    const PAYMENT_STATUS_RESERVED = 'Reserved';
    const PAYMENT_STATUS_AUTHORIZED = 'Authorized';
    const PAYMENT_STATUS_PREPARED = 'Prepared';
    const PAYMENT_STATUS_STARTED = 'Started';
    const PAYMENT_STATUS_IN_PROGRESS = 'InProgress';
    const PAYMENT_STATUS_PARTIALLY_SUCCEEDED = 'PartiallySucceeded'; // Barion sends this message only on stacked transactions. So: one fayment will never get this.
    const PAYMENT_STATUS_SUCCEEDED = 'Succeeded';
    const PAYMENT_STATUS_REGISTERED = 'Registered';

    const PAYMENT_STATUS_COLLECTION_CLOSABLE = [
        self::PAYMENT_STATUS_EXPIRED, 
        self::PAYMENT_STATUS_SUCCEEDED
    ];

    const PAYMENT_STATUS_COLLECTION_SUCCEEDED = [
        self::PAYMENT_STATUS_SUCCEEDED,
        self::PAYMENT_STATUS_REGISTERED
    ];

    /**
     * Note: if it's already Prepared, it can be re-prepared. 
     * If Prepared status would prevent preparing, than your customer would not be able to pay if they close the PaymentModal.
     * Barion accepts preparation as many times, as we please. The last preparation will be valid.
    */
    const PAYMENT_STATUS_COLLECTION_CAN_BE_PREPARED = [
        self::PAYMENT_STATUS_CREATED,
        self::PAYMENT_STATUS_PREPARED,
    ];

    /**
     * Needs preparation
     * Update: let's use PAYMENT_STATUS_COLLECTION_CAN_BE_PREPARED collection instead.
    */
    // const PAYMENT_STATUS_COLLECTION_TO_BE_PREPARED = [
    //     self::PAYMENT_STATUS_CREATED
    // ];

    /**
     * Needs to be transferred to the provider's website
    */
    const PAYMENT_STATUS_COLLECTION_PREPARED_UNPAID = [
        self::PAYMENT_STATUS_PREPARED
    ];

    /**
     * These need status check
    */
    const PAYMENT_STATUS_COLLECTION_WAITING_FOR_RESULT = [
        self::PAYMENT_STATUS_WAITING,
        self::PAYMENT_STATUS_RESERVED,
        self::PAYMENT_STATUS_AUTHORIZED,
        self::PAYMENT_STATUS_STARTED
    ];

    /**
     * History only
    */
    const PAYMENT_STATUS_COLLECTION_FAILED_FOREVER = [
        self::PAYMENT_STATUS_FAILED, 
        self::PAYMENT_STATUS_INVALID,
        self::PAYMENT_STATUS_EXPIRED,
        self::PAYMENT_STATUS_CANCELLED
    ];

    const PAYMENT_STATUS_COLLECTION_ALL = [
        self::PAYMENT_STATUS_INVALID,
        self::PAYMENT_STATUS_FAILED,
        self::PAYMENT_STATUS_EXPIRED,
        self::PAYMENT_STATUS_CANCELLED,
        self::PAYMENT_STATUS_CREATED,
        self::PAYMENT_STATUS_WAITING,
        self::PAYMENT_STATUS_RESERVED,
        self::PAYMENT_STATUS_AUTHORIZED,
        self::PAYMENT_STATUS_PREPARED,
        self::PAYMENT_STATUS_STARTED,
        self::PAYMENT_STATUS_IN_PROGRESS,
        self::PAYMENT_STATUS_PARTIALLY_SUCCEEDED,
        self::PAYMENT_STATUS_SUCCEEDED,
        self::PAYMENT_STATUS_REGISTERED
    ];

    const PAYMENT_STATUS_TRANSLATION_REFERENCES = [
        self::PAYMENT_STATUS_INVALID => 'payment.status.invalid',
        self::PAYMENT_STATUS_FAILED => 'payment.status.failed',
        self::PAYMENT_STATUS_EXPIRED => 'payment.status.expired',
        self::PAYMENT_STATUS_CANCELLED => 'payment.status.cancelled',
        self::PAYMENT_STATUS_CREATED => 'payment.status.created',
        self::PAYMENT_STATUS_WAITING => 'payment.status.waiting',
        self::PAYMENT_STATUS_RESERVED => 'payment.status.reserved',
        self::PAYMENT_STATUS_AUTHORIZED => 'payment.status.authorized',
        self::PAYMENT_STATUS_PREPARED => 'payment.status.prepared',
        self::PAYMENT_STATUS_STARTED => 'payment.status.started',
        self::PAYMENT_STATUS_IN_PROGRESS => 'payment.status.in.progress',
        self::PAYMENT_STATUS_PARTIALLY_SUCCEEDED => 'payment.status.partially.succeeded',
        self::PAYMENT_STATUS_SUCCEEDED => 'payment.status.succeeded',
        self::PAYMENT_STATUS_REGISTERED => 'payment.status.registered'
    ];

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `payment` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `shipment_id` int(11) DEFAULT NULL,
        `payment_code` varchar(255) DEFAULT NULL,
        `ipn_called` int(1) DEFAULT 0,
        `gateway_provider` varchar(100) DEFAULT NULL,
        `payment_method` varchar(100) DEFAULT NULL,
        `qr_url` varchar(255) DEFAULT NULL,
        `gateway_url` varchar(255) DEFAULT NULL,
        `recurrence_result` varchar(100) DEFAULT NULL,
        `three_ds_auth_client_data` text DEFAULT NULL,
        `total_gross_value` int(20) DEFAULT NULL,
        `currency` varchar(20) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `redirected_at` datetime DEFAULT NULL,
        `closed_at` datetime DEFAULT NULL,
        `status` varchar(10) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=52000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $shipment;
    protected $paymentCode;

    /**
     * @var int
     * ipn: Instant Payment Notification
    */
    protected $ipnCalled;

    /**
     * @var string
     * e.g.: Barion
    */
    protected $gatewayProvider;

    /**
     * @var string 
     * In this system it mostly equals @var $gatewayProvider since all methods are modelled as a gateway provider.
    */
    protected $paymentMethod;

    /**
     * @var string 
     * URL for a QR-code payment.
     * Not used yet, I put that to the entity, because Bation provides this data, and I did not want to miss it.
    */
    protected $qrUrl;

    /**
     * The URL for the provider's server
    */
    protected $gatewayUrl;

    /**
     * Not important, just returns from Barion.
    */
    protected $recurrenceResult;

    /**
     * Not important, just returns from Barion.
    */
    protected $threeDsAuthClientData;
    
    protected $totalGrossValue;
    protected $currency;
    protected $createdAt;
    protected $redirectedAt;
    protected $closedAt;
    protected $status;

    public function __construct()
    {
        $this->ipnCalled = 0;
        $this->createdAt = $this->getCurrentTimestamp();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setShipment(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    public function getShipment()
    {
        return $this->shipment;
    }

    public function setPaymentCode($paymentCode)
    {
        $this->paymentCode = $paymentCode;
    }

    public function getPaymentCode()
    {
        return $this->paymentCode;
    }

    public function setIpnCalled($ipnCalled)
    {
        $this->ipnCalled = $ipnCalled;
    }

    public function getIpnCalled()
    {
        return $this->ipnCalled;
    }

    public function setGatewayProvider($gatewayProvider)
    {
        $this->gatewayProvider = $gatewayProvider;
    }

    public function getGatewayProvider()
    {
        return $this->gatewayProvider;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setQrUrl($qrUrl)
    {
        $this->qrUrl = $qrUrl;
    }

    public function getQrUrl()
    {
        return $this->qrUrl;
    }

    public function setGatewayUrl($gatewayUrl)
    {
        $this->gatewayUrl = $gatewayUrl;
    }

    public function getGatewayUrl()
    {
        return $this->gatewayUrl;
    }

    public function setRecurrenceResult($recurrenceResult)
    {
        $this->recurrenceResult = $recurrenceResult;
    }

    public function getRecurrenceResult()
    {
        return $this->recurrenceResult;
    }

    public function setThreeDsAuthClientData($threeDsAuthClientData)
    {
        $this->threeDsAuthClientData = $threeDsAuthClientData;
    }

    public function getThreeDsAuthClientData()
    {
        return $this->threeDsAuthClientData;
    }

    public function setTotalGrossValue($totalGrossValue)
    {
        $this->totalGrossValue = $totalGrossValue;
    }

    public function getTotalGrossValue()
    {
        return $this->totalGrossValue;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setRedirectedAt($redirectedAt)
    {
        $this->redirectedAt = $redirectedAt;
    }

    public function getRedirectedAt()
    {
        return $this->redirectedAt;
    }

    public function setClosedAt($closedAt = null)
    {
        // dump($closedAt);exit;
        $this->closedAt = $closedAt;
    }

    public function getClosedAt()
    {
        return $this->closedAt;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
