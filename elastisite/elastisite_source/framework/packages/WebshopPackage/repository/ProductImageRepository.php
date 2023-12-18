<?php
namespace framework\packages\WebshopPackage\repository;

use framework\component\parent\DbRepository;
use framework\kernel\utility\BasicUtils;

class ProductImageRepository extends DbRepository
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

    public function generateSlug()
    {
        $slug = BasicUtils::generateRandomString(12);
        if ($this->findOneBy(['conditions' => [['key' => 'slug', 'value' => $slug]]])) {
            return $this->generateSlug();
        }
        return $slug;
    }
}
