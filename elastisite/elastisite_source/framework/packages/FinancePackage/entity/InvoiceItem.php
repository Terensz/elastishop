<?php
namespace framework\packages\FinancePackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\WebshopPackage\entity\Product;

class InvoiceItem extends DbEntity
{
    const UNIT_OF_MEASURE_PIECE = 'piece';

    const UNIT_OF_MEASURE_KILOGRAM = 'kilogram';

    const UNITS_OF_MEASURE = ['piece', 'kilogram'];

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `invoice_item` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `invoice_header_id` int(11) DEFAULT NULL,
        `line_index` int(5) DEFAULT NULL,
        `referenced_line_index` int(5) DEFAULT NULL,
        `product_id` int(11) DEFAULT NULL,
        `product_name` varchar(250) DEFAULT NULL,
        `quantity` int(5) DEFAULT NULL,
        `unit_of_measure` varchar(20) DEFAULT NULL,
        `unit_net` decimal(13,2) DEFAULT NULL,
        `item_net` decimal(13,2) DEFAULT NULL,
        `vat_percent` decimal(5,2) DEFAULT NULL,
        `item_vat` decimal(13,2) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=71000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $invoiceHeader;
    protected $lineIndex;
    protected $referencedLineIndex;
    protected $productId;
    // protected $product;
    protected $productName;
    protected $quantity;
    protected $unitOfMeasure;
    protected $unitNet;
    protected $itemNet;
    protected $vatPercent;
    protected $itemVat;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setInvoiceHeader(InvoiceHeader $invoiceHeader)
    {
        $this->invoiceHeader = $invoiceHeader;
    }

    public function getInvoiceHeader()
    {
        return $this->invoiceHeader;
    }

    public function setLineIndex($lineIndex)
    {
        $this->lineIndex = $lineIndex;
    }

    public function getLineIndex()
    {
        return $this->lineIndex;
    }

    public function setReferencedLineIndex($referencedLineIndex)
    {
        $this->referencedLineIndex = $referencedLineIndex;
    }

    public function getReferencedLineIndex()
    {
        return $this->referencedLineIndex;
    }

    // public function setProduct(Product $product)
    // {
    //     $this->product = $product;
    // }

    // public function getProduct()
    // {
    //     return $this->product;
    // }

    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function setProductName($productName)
    {
        $this->productName = $productName;
    }

    public function getProductName()
    {
        return $this->productName;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setUnitOfMeasure($unitOfMeasure)
    {
        $this->unitOfMeasure = $unitOfMeasure;
    }

    public function getUnitOfMeasure()
    {
        return $this->unitOfMeasure;
    }

    public function setUnitNet($unitNet)
    {
        $this->unitNet = $unitNet;
    }

    public function getUnitNet()
    {
        return $this->unitNet;
    }

    public function setItemNet($itemNet)
    {
        $this->itemNet = $itemNet;
    }

    public function getItemNet()
    {
        return $this->itemNet;
    }

    public function setVatPercent($vatPercent)
    {
        $this->vatPercent = $vatPercent;
    }

    public function getVatPercent()
    {
        return $this->vatPercent;
    }

    public function setItemVat($itemVat)
    {
        $this->itemVat = $itemVat;
    }

    public function getItemVat()
    {
        return $this->itemVat;
    }
}
