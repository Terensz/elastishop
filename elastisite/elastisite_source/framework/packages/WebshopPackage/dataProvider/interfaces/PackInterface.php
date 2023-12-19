<?php
namespace framework\packages\WebshopPackage\dataProvider\interfaces;

interface PackInterface
{
    public function getId();
    public function getUserAccount();
    public function getTemporaryAccount();
}