<?php
namespace framework\packages\WebshopPackage\entity;

use App;
use framework\component\parent\DbEntity;
use framework\packages\WebshopPackage\entity\ShipmentItem;
use framework\packages\UserPackage\entity\UserAccount;
use framework\packages\UserPackage\entity\TemporaryAccount;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\WebshopPackage\dataProvider\interfaces\PackInterface;
use framework\packages\WebshopPackage\entity\Cart;

class Shipment extends DbEntity implements PackInterface
{
    // const SHIPMENT_STATUS_INACTIVE = 0;
    const SHIPMENT_STATUS_ORDER_CANCELLED = 15;
    const SHIPMENT_STATUS_ORDER_PREPARED = 10;
    // const SHIPMENT_STATUS_PAYMENT_METHOD_SELECTED = 11;
    // const SHIPMENT_STATUS_PAYMENT_ACCEPTED = 12;
    const SHIPMENT_STATUS_ORDER_PLACED = 20;
    const SHIPMENT_STATUS_ORDER_CONFIRMED = 21;
    const SHIPMENT_STATUS_WAITING_FOR_PRODUCT = 35;
    const SHIPMENT_STATUS_PREPARED_FOR_DELIVERY = 4;
    const SHIPMENT_STATUS_POSTED = 5;
    const SHIPMENT_STATUS_CUSTOMER_IS_UNREACHABLE = 55;
    const SHIPMENT_STATUS_DELIVERED = 90;

    const STATUS_COLLECTION_SHIPMENTS_HANDLING_PROMPTED_TO_USER = [
        self::SHIPMENT_STATUS_ORDER_PREPARED,
    ];

    const STATUS_COLLECTION_SHIPMENTS_LISTED_AS_UNHANDLED_TO_USER = [
        self::SHIPMENT_STATUS_ORDER_PREPARED,
    ];

    const STATUS_COLLECTION_SHIPMENTS_IN_PROGRESS = [
        self::SHIPMENT_STATUS_ORDER_PREPARED,
        // self::SHIPMENT_STATUS_PAYMENT_METHOD_SELECTED,
        self::SHIPMENT_STATUS_ORDER_PLACED,
        self::SHIPMENT_STATUS_WAITING_FOR_PRODUCT,
        self::SHIPMENT_STATUS_PREPARED_FOR_DELIVERY,
        self::SHIPMENT_STATUS_POSTED,
        self::SHIPMENT_STATUS_CUSTOMER_IS_UNREACHABLE
    ];

    /**
     * Required to determine of a status if the customer already paid or not.
     * However, this approach fails while the method is cash-on-delivery :-)
    */
    const STATUS_COLLECTION_PAID_STATUSES = [
        self::SHIPMENT_STATUS_ORDER_PLACED,
        self::SHIPMENT_STATUS_WAITING_FOR_PRODUCT,
        self::SHIPMENT_STATUS_PREPARED_FOR_DELIVERY,
        self::SHIPMENT_STATUS_POSTED,
        self::SHIPMENT_STATUS_CUSTOMER_IS_UNREACHABLE,
        self::SHIPMENT_STATUS_DELIVERED
    ];

    const STATUS_COLLECTION_PAID_UNFINISHED_STATUSES = [
        self::SHIPMENT_STATUS_ORDER_PLACED,
        self::SHIPMENT_STATUS_WAITING_FOR_PRODUCT,
        self::SHIPMENT_STATUS_PREPARED_FOR_DELIVERY,
        self::SHIPMENT_STATUS_POSTED,
        self::SHIPMENT_STATUS_CUSTOMER_IS_UNREACHABLE,
        self::SHIPMENT_STATUS_DELIVERED
    ];

    const STATUS_COLLECTION_UNFINISHED_ORDER_STATUSES = [
        self::SHIPMENT_STATUS_ORDER_PREPARED
    ];

    // const STATUS_COLLECTION_FINAL_STATUSES = [
    //     self::SHIPMENT_STATUS_ORDER_CANCELLED,
    //     self::SHIPMENT_STATUS_CLOSED
    // ];

    /**
     * The complementer of the previous set
    */
    const STATUS_COLLECTION_UNPAID_STATUSES = [
        self::SHIPMENT_STATUS_ORDER_CANCELLED,
        self::SHIPMENT_STATUS_ORDER_PREPARED
    ];

    /**
     * The complementer of the previous set
    */
    const STATUS_COLLECTION_USER_ALLOWED_TO_EDIT = [
        self::SHIPMENT_STATUS_ORDER_PREPARED
    ];

    // const SHIPMENT_STATUS_CLOSED = 'SHIPMENT_STATUS_DELIVERED';
    const SHIPMENT_STATUS_CLOSED = self::SHIPMENT_STATUS_DELIVERED;

    const MAXIMUM_PRODUCT_CATEGORY_DEPTH = 1;

    const PRIORITY_NORMAL = 1;
    const PRIORITY_HIGH = 2;

    public static $statuses = array(
        self::SHIPMENT_STATUS_ORDER_CANCELLED => array(
            'publicTitle' => 'order.cancelled',
            'adminTitle' => 'order.cancelled',
            'nextStatuses' => []
        ),
        self::SHIPMENT_STATUS_ORDER_PREPARED => array(
            'publicTitle' => 'order.prepared',
            'adminTitle' => 'order.prepared',
            'nextStatuses' => [
                self::SHIPMENT_STATUS_ORDER_CANCELLED,
                // self::SHIPMENT_STATUS_PAYMENT_METHOD_SELECTED
            ]
        ),
        // self::SHIPMENT_STATUS_PAYMENT_METHOD_SELECTED => array(
        //     'publicTitle' => 'payment.method.selected',
        //     'adminTitle' => 'payment.method.selected',
        //     'nextStatuses' => [
        //         self::SHIPMENT_STATUS_ORDER_CANCELLED,
        //         self::SHIPMENT_STATUS_ORDER_PLACED
        //     ]
        // ),
        self::SHIPMENT_STATUS_ORDER_PLACED => array(
            'publicTitle' => 'order.placed',
            'adminTitle' => 'ordered',
            'nextStatuses' => [
                self::SHIPMENT_STATUS_WAITING_FOR_PRODUCT,
                self::SHIPMENT_STATUS_PREPARED_FOR_DELIVERY
            ]
        ),
        self::SHIPMENT_STATUS_ORDER_CONFIRMED => array(
            'publicTitle' => 'order.placed',
            'adminTitle' => 'ordered',
            'nextStatuses' => [
                self::SHIPMENT_STATUS_WAITING_FOR_PRODUCT,
                self::SHIPMENT_STATUS_PREPARED_FOR_DELIVERY
            ]
        ),
        self::SHIPMENT_STATUS_WAITING_FOR_PRODUCT => array(
            'publicTitle' => 'order.placed',
            'adminTitle' => 'waiting.for.product',
            'nextStatuses' => [
                self::SHIPMENT_STATUS_PREPARED_FOR_DELIVERY
            ]
        ),
        self::SHIPMENT_STATUS_PREPARED_FOR_DELIVERY => array(
            'publicTitle' => 'order.placed',
            'adminTitle' => 'prepared.for.delivery'
        ),
        self::SHIPMENT_STATUS_POSTED => array(
            'publicTitle' => 'shipment.posted',
            'adminTitle' => 'shipment.posted',
            'nextStatuses' => [
                self::SHIPMENT_STATUS_CUSTOMER_IS_UNREACHABLE,
                self::SHIPMENT_STATUS_DELIVERED
            ]
        ),
        self::SHIPMENT_STATUS_CUSTOMER_IS_UNREACHABLE => array(
            'publicTitle' => 'customer.is.unreachable',
            'adminTitle' => 'customer.is.unreachable',
            'nextStatuses' => [
                self::SHIPMENT_STATUS_DELIVERED
            ]
        ),
        self::SHIPMENT_STATUS_DELIVERED => array(
            'publicTitle' => 'shipment.delivered',
            'adminTitle' => 'shipment.delivered',
            'nextStatuses' => []
        ),
    );
    
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `shipment` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `is_test_record` smallint(1) DEFAULT 0,
        `priority` smallint(2) DEFAULT 1,
        `code` varchar(30) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `visitor_code` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `user_account_id` int(11) DEFAULT NULL,
        `temporary_account_id` int(11) DEFAULT NULL,
        `admin_note` varchar(250) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `payment_method` varchar(100) DEFAULT NULL,
        `confirmation_sent_at` datetime DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` smallint(2) DEFAULT 0,
        `closed` smallint(2) DEFAULT 0,
        `completed_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1300 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $website;
    protected $isTestRecord;
    protected $cart;
    protected $payment = array();
    protected $priority;
    protected $shipmentItem = array();
    protected $code;
    protected $visitorCode;
    protected $userAccount;
    protected $temporaryAccount;
    protected $adminNote;
    /**
     * This seems duplicated, because payment table also contains that.
     * BUT: this is needed! This is the customer-chosen payment method, at the checkout.
    */
    protected $paymentMethod;
    /**
     * The confirmation letter is sent when the GWO replies on successful payment.
    */
    protected $confirmationSentAt;
    protected $createdAt;
    protected $status;
    protected $closed;
    protected $completedAt;

    public function __construct()
    {
        $this->website = App::getWebsite();
        $this->createdAt = $this->getCurrentTimestamp();
        $this->closed = 0;
        $this->priority = self::PRIORITY_NORMAL;
    }

    public function getSuccessfulPayment() : ? Payment
    {
        // dump($this->payment);
        foreach ($this->payment as $payment) {
            if ($payment->getStatus() == Payment::PAYMENT_STATUS_SUCCEEDED) {
                return $payment;
            }
        }

        return null;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function checkCorrectWebsite()
    {
        return App::getWebsite() == $this->website ? true : false;
    }

    public function setIsTestRecord($isTestRecord)
    {
        if ($isTestRecord === true) {
            $isTestRecord = 1;
        }
        if ($isTestRecord === false) {
            $isTestRecord = 0;
        }
        $this->isTestRecord = $isTestRecord;
    }

    public function getIsTestRecord()
    {
        $isTestRecord = $this->isTestRecord;
        if ((int)$isTestRecord === 1) {
            $isTestRecord = true;
        }
        if ((int)$isTestRecord === 0) {
            $isTestRecord = false;
        }
        return $isTestRecord;
    }

    public function setCart(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function getCart()
    {
        return $this->cart;
    }

    public function addPayment(Payment $payment)
    {
        $this->payment[] = $payment;
    }

    // public function setPayment($payment)
    // {
    //     $this->payment = $payment;
    // }

    public function getPayment()
    {
        return $this->payment;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setVisitorCode($visitorCode)
    {
        $this->visitorCode = $visitorCode;
    }

    public function getVisitorCode()
    {
        return $this->visitorCode;
    }
    
    public function addShipmentItem(ShipmentItem $shipmentItem = null)
    {
        $this->shipmentItem[] = $shipmentItem;
    }
    
    public function getShipmentItem()
    {
        return $this->shipmentItem;
    }

    public function setUserAccount(UserAccount $userAccount = null)
    {
        $this->userAccount = $userAccount;
    }
    
    public function getUserAccount()
    {
        return $this->userAccount;
    }

    // public function setOrganization(Organization $organization = null)
    // {
    //     $this->organization = $organization;
    // }
    
    // public function getOrganization()
    // {
    //     return $this->organization;
    // }

    public function setTemporaryAccount(TemporaryAccount $temporaryAccount = null)
    {
        $this->temporaryAccount = $temporaryAccount;
    }

    public function getTemporaryAccount()
    {
        return $this->temporaryAccount;
    }

    // public function setCountry(Country $country)
    // {
    //     $this->country = $country;
    // }

    // public function getCountry()
    // {
    //     return $this->country;
    // }

    // public function setZipCode($zipCode)
    // {
    //     $this->zipCode = $zipCode;
    // }

    // public function getZipCode()
    // {
    //     return $this->zipCode;
    // }

    // public function setCity($city)
    // {
    //     $this->city = $city;
    // }

    // public function getCity()
    // {
    //     return $this->city;
    // }

    // public function setPaymentMethodCode($paymentMethodCode)
    // {
    //     $this->paymentMethodCode = $paymentMethodCode;
    // }

    // public function getPaymentMethodCode()
    // {
    //     return $this->paymentMethodCode;
    // }

    // public function setTemporaryPerson(TemporaryPerson $temporaryPerson)
    // {
    //     $this->temporaryPerson = $temporaryPerson;
    // }

    // public function getTemporaryPerson()
    // {
    //     return $this->temporaryPerson;
    // }

    // public function setCustomerNote($customerNote)
    // {
    //     $this->customerNote = $customerNote;
    // }

    // public function getCustomerNote()
    // {
    //     return $this->customerNote;
    // }

    public function setAdminNote($adminNote)
    {
        $this->adminNote = $adminNote;
    }

    public function getAdminNote()
    {
        return $this->adminNote;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setConfirmationSentAt($confirmationSentAt)
    {
        $this->confirmationSentAt = $confirmationSentAt;
    }

    public function getConfirmationSentAt()
    {
        return $this->confirmationSentAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getStatusText()
    {
        // $this->setService('WebshopPackage/service/WebshopService');
        // $webshopService = $this->getService('WebshopService');
        $status = self::$statuses[$this->status];
        return trans($status[($this->getContainer()->isGranted('viewProjectCoworkerContent') ? 'admin' : 'public').'Title']);
    }

    public function setClosed($closed)
    {
        $this->closed = $closed;
    }

    public function getClosed()
    {
        return $this->closed;
    }

    public function setCompletedAt($completedAt)
    {
        $this->completedAt = $completedAt;
    }

    public function getCompletedAt()
    {
        return $this->completedAt;
    }
}
