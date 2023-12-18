<?php
namespace framework\packages\WebshopPackage\repository;

use framework\component\parent\DbRepository;

class ShipmentItemRepository extends DbRepository
{
    public function isDeletable($id)
    {
        return true;
    }

    // public function cleanUpOrphans()
    // {
    //     return true;
    // }
}
