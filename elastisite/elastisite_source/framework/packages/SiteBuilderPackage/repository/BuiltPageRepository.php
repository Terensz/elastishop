<?php
namespace framework\packages\SiteBuilderPackage\repository;

use App;
use framework\component\parent\DbRepository;

class BuiltPageRepository extends DbRepository
{
    public function isDeletable($id)
    {
        return true;
    }

    public function remove($id)
    {
        $dbm = $this->getDbManager();

        $stm = "DELETE FROM built_page_param_chain WHERE built_page_id = :built_page_id ";
        $dbm->execute($stm, ['built_page_id' => $id]);

        $stm = "DELETE FROM built_page_widget WHERE built_page_id = :built_page_id ";
        $dbm->execute($stm, ['built_page_id' => $id]);

        return parent::remove($id);
    }

    // public function findAllOnWebsite()
    // {
    //     return $this->findBy(['conditions' => [['key' => 'website', 'value' => App::getWebsite()]]]);
    // }

    public function getGridDataFilteredQuery($filter)
    {
        $whereClause = $this->createWhereClauseFromFilter($filter ? $filter['conditions'] : null);
        return array(
            'statement' => "SELECT * FROM (SELECT id, route_name, title, is_menu_item
                            FROM built_page maintable 
                            WHERE maintable.website = '".App::getWebsite()."'
                            GROUP BY maintable.id) table0
                            ".$whereClause['whereStr']." ",
            'params' => $whereClause['params']
        );
    }

    public function findOfferableBuiltPages()
    {
        $dbm = $this->getDbManager();
        $stm = "SELECT bp.id as bp_id
        FROM built_page bp
        LEFT JOIN menu_item mi ON mi.website = bp.website AND mi.route_name = bp.route_name
        WHERE mi.id IS NULL ";

        $builtPages = [];
        $foundBuiltPageIds = $dbm->findAll($stm, array());
        foreach ($foundBuiltPageIds as $foundBuiltPageIdArray) {
            $builtPages[] = $this->find($foundBuiltPageIdArray['bp_id']);
        }

        return $builtPages;
    }
}
