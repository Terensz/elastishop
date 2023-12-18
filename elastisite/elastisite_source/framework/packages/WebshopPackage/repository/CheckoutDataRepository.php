<?php
namespace framework\packages\WebshopPackage\repository;

use framework\component\parent\TechnicalRepository;

class CheckoutDataRepository extends TechnicalRepository
{
    public function getPrimaryKeyField()
    {
        return 'id';
    }
}
