<?php
namespace framework\packages\WebshopPackage\dataProvider\interfaces;

interface PackItemInterface
{
    public function getId();
    public function getProduct();
    public function getQuantity();
}