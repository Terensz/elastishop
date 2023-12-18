<?php
namespace framework\packages\ExpandedWebshopPackage\repository;

use framework\component\parent\DbRepository;

class ExpandedProductEntryHeadRepository extends DbRepository
{
    public function isDeletable($id)
    {
        // $stm = "SELECT pc.id
        // from product p
        // left join order o on o.product_id = p.id 
        // where p.id = :id 
        // group by p.id";
        // $params = array(':id' => $id);
        // $dbm = $this->getDbManager();
        // $result = $dbm->findOne($stm, $params);
        // $return = $result === false ? true : false;
        return true;
    }
}
