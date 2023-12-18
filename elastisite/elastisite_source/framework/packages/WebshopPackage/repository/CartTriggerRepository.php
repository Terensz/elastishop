<?php
namespace framework\packages\WebshopPackage\repository;

use App;
use framework\component\parent\DbRepository;

class CartTriggerRepository extends DbRepository
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

        /**
     * This is the query method for the datagrid. 
    */
    public function getGridDataFilteredQuery($filter)
    {
        $whereClause = $this->createWhereClauseFromFilter($filter ? $filter['conditions'] : null);
        return array(
            'statement' => "SELECT * FROM (SELECT 
                                maintable.id, 
                                maintable.name,
                                maintable.direction_of_change,
                                maintable.effect_causing_stuff,
                                maintable.effect_causing_value,
                                maintable.effect_operator,
                                maintable.status
                            FROM cart_trigger maintable
                            WHERE maintable.website = '".App::getWebsite()."') table0
                            ".$whereClause['whereStr']." ",
            'params' => $whereClause['params']
        );
    }
}
