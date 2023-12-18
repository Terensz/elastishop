<?php
namespace framework\packages\WebshopPackage\entity;

use App;
use framework\component\parent\DbEntity;
use framework\packages\BusinessPackage\entity\Organization;
use framework\packages\UserPackage\entity\Address;
use framework\packages\UserPackage\entity\TemporaryAccount;
use framework\packages\WebshopPackage\entity\CartItem;
use framework\packages\UserPackage\entity\UserAccount;
use framework\packages\WebshopPackage\entity\Shipment;

class Cart extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `cart` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `user_account_id` int(11) DEFAULT NULL,
        `temporary_account_id` int(11) DEFAULT NULL,
        `shipment_id` int(11) DEFAULT NULL,
        `visitor_code` varchar(100) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=21000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";



    /*
    use elastisite_devel;
    drop table cart ;
    CREATE TABLE `cart` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_account_id` int(11) DEFAULT NULL,
    `visitor_code` varchar(20) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    -- `status` int(2) DEFAULT 0,
    -- `completed_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=21000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;
    */

    protected $id;
    protected $website;
    protected $userAccount;
    protected $temporaryAccount;
    protected $shipment;
    // protected $address;
    protected $visitorCode;
    // protected $recipient;
    // protected $note;
    protected $cartItem = array();
    protected $createdAt;
    // protected $status;
    // protected $completedAt;

    public function __construct()
    {
        $this->website = App::getWebsite();
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

    public function setUserAccount(UserAccount $userAccount)
    {
        $this->userAccount = $userAccount;
    }

    public function getUserAccount()
    {
        return $this->userAccount;
    }

    public function setTemporaryAccount(TemporaryAccount $temporaryAccount = null)
    {
        $this->temporaryAccount = $temporaryAccount;
    }

    public function getTemporaryAccount() : ? TemporaryAccount
    {
        return $this->temporaryAccount;
    }

    public function setShipment(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    public function getShipment()
    {
        return $this->shipment;
    }

    // public function setAddress(Address $address)
    // {
    //     $this->address = $address;
    // }

    // public function getAddress()
    // {
    //     return $this->address;
    // }

    public function setVisitorCode($visitorCode)
    {
        $this->visitorCode = $visitorCode;
    }

    public function getVisitorCode()
    {
        return $this->visitorCode;
    }

    // public function setRecipient($recipient)
    // {
    //     $this->recipient = $recipient;
    // }

    // public function getRecipient()
    // {
    //     return $this->recipient;
    // }

    // public function setNote($note)
    // {
    //     $this->note = $note;
    // }

    // public function getNote()
    // {
    //     return $this->note;
    // }

    public function addCartItem(CartItem $cartItem)
    {
        $this->cartItem[] = $cartItem;
    }

    public function getCartItem()
    {
        return $this->cartItem;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    // public function setStatus($status)
    // {
    //     $this->status = $status;
    // }

    // public function getStatus()
    // {
    //     return $this->status;
    // }

    // public function setCompletedAt($completedAt)
    // {
    //     $this->completedAt = $completedAt;
    // }

    // public function getCompletedAt()
    // {
    //     return $this->completedAt;
    // }
}
