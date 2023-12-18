<?php
namespace framework\packages\SiteBuilderPackage\repository;

use App;
use framework\component\parent\DbRepository;

class MenuItemRepository extends DbRepository
{
    public function sort()
    {
        $dbm = $this->getDbManager();
        $stm = "SELECT bp.id as bp_id
        FROM built_page bp
        LEFT JOIN menu_item mi ON mi.website = bp.website AND mi.route_name = bp.route_name
        WHERE mi.id IS NULL ";

        $builtPages = [];
        $foundBuiltPageIds = $dbm->findAll($stm, array());
    }

    public function getNextSequenceNumber()
    {
        // $allOnThis $this->findBy([
        //     'conditions' => [
        //         ['key' => 'website', 'value' => App::getWebsite()]
        //     ]
        // ]);

        $dbm = $this->getDbManager();
        $stm = "SELECT max(mi.sequence_number) max_seq
        -- mi.id as mi_id
        FROM menu_item mi
        WHERE mi.website = :website ";

        return (int)$dbm->findOne($stm, array('website' => App::getWebsite()))['max_seq'] + 1;
    }
}
