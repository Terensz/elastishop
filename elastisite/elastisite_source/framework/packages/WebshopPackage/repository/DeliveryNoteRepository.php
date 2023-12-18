<?php
namespace framework\packages\WebshopPackage\repository;

use framework\component\parent\DbRepository;

class DeliveryNoteRepository extends DbRepository
{
    public function isDeletable($id)
    {
        return true;
    }
}
