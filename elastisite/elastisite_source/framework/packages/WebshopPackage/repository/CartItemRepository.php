<?php
namespace framework\packages\WebshopPackage\repository;

use framework\component\parent\DbRepository;

class CartItemRepository extends DbRepository
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

    public function removeUnbound()
    {
        $dbm = $this->getDbManager();
        $stm1 = "SELECT ci.id
        from cart_item ci 
        left join product_price pp on pp.id = ci.product_price_id 
        left join product_price_active ppa on ppa.product_price_id = pp.id
        where ppa.id is null";
        // $params = array(':id' => $id);
        $result = $dbm->findAll($stm1, array());
        if (is_array($result) && !empty($result)) {
            $params = array();
            $counter = 0;
            foreach ($result as $resultRow) {
                $params[':id'.$counter] = $resultRow['id'];
                $counter++;
            }
            // dump(array_keys($params));
            // dump($params);exit;
            $stm2 = "DELETE from cart_item where id in (".implode(',', array_keys($params)).")";
            $dbm->execute($stm2, $params);
        }
    }
}
