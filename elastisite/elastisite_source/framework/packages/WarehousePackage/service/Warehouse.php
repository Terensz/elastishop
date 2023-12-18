<?php
namespace framework\packages\WarehousePackage\service;

use framework\component\parent\Service;
use framework\packages\WarehousePackage\entity\Ware;

class Warehouse extends Service
{
    public $warehousesData = [
        0 => [
            'id' => 101,
            'name' => 'OcsaiStreetWarehouse'
        ],
        1 => [
            'id' => 102,
            'name' => 'VaciStreetPickUpPoint'
        ]
    ];
    
    private $id;
    private $name;
    private $inventory;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->inventory = [];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getInventory()
    {
        return $this->inventory;
    }

    public function addProduct(Ware $ware, $quantity)
    {
        $wareId = $ware->getId();
        if (isset($this->inventory[$wareId])) {
            $this->inventory[$wareId]['quantity'] += $quantity;
        } else {
            $this->inventory[$wareId] = [
                'ware' => $ware,
                'quantity' => $quantity
            ];
        }
    }

    public function removeProduct(Ware $ware, $quantity)
    {
        $wareId = $ware->getId();
        if (isset($this->inventory[$wareId])) {
            $currentQuantity = $this->inventory[$wareId]['quantity'];
            if ($quantity >= $currentQuantity) {
                unset($this->inventory[$wareId]);
            } else {
                $this->inventory[$wareId]['quantity'] -= $quantity;
            }
        }
    }

    public function getProductQuantity(Ware $ware)
    {
        $wareId = $ware->getId();
        if (isset($this->inventory[$wareId])) {
            return $this->inventory[$wareId]['quantity'];
        }
        return 0;
    }

    public function reserveProduct(Ware $ware, $quantity)
    {
        $wareId = $ware->getId();
        if (isset($this->inventory[$wareId])) {
            $currentQuantity = $this->inventory[$wareId]['quantity'];
            if ($quantity <= $currentQuantity) {
                $this->inventory[$wareId]['quantity'] -= $quantity;
                return true;
            }
        }
        return false;
    }

    public function releaseProduct(Ware $ware, $quantity)
    {
        $wareId = $ware->getId();
        if (isset($this->inventory[$wareId])) {
            $this->inventory[$wareId]['quantity'] += $quantity;
        }
    }
}