<?php
namespace framework\packages\WarehousePackage\service;

use framework\packages\WarehousePackage\entity\Ware;

class WarehouseManager
{
    private $warehouses;

    public function __construct()
    {
        $this->warehouses = [];
    }

    public function addWarehouse(Warehouse $warehouse)
    {
        $warehouseId = $warehouse->getId();
        if (!isset($this->warehouses[$warehouseId])) {
            $this->warehouses[$warehouseId] = $warehouse;
        }
    }

    public function removeWarehouse(Warehouse $warehouse)
    {
        $warehouseId = $warehouse->getId();
        if (isset($this->warehouses[$warehouseId])) {
            unset($this->warehouses[$warehouseId]);
        }
    }

    public function getWarehouse($warehouseId)
    {
        if (isset($this->warehouses[$warehouseId])) {
            return $this->warehouses[$warehouseId];
        }
        return null;
    }

    public function getWarehouses()
    {
        return $this->warehouses;
    }

    public function reserveWare(Ware $ware, $quantity, $warehouseId)
    {
        $warehouse = $this->getWarehouse($warehouseId);
        if ($warehouse !== null) {
            return $warehouse->reserveProduct($ware, $quantity);
        }
        return false;
    }

    public function releaseWare(Ware $ware, $quantity, $warehouseId)
    {
        $warehouse = $this->getWarehouse($warehouseId);
        if ($warehouse !== null) {
            $warehouse->releaseProduct($ware, $quantity);
        }
    }
}
