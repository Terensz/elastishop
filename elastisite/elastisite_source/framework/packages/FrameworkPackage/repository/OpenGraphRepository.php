<?php
namespace framework\packages\FrameworkPackage\repository;

use App;
use framework\component\parent\DbRepository;
use framework\kernel\utility\BasicUtils;

class OpenGraphRepository extends DbRepository
{
    public function isDeletable($id)
    {
        $dbm = $this->getDbManager();
        $stm = "SELECT count(cpog.id) as count_cpog
                FROM open_graph og 
                LEFT JOIN custom_page_open_graph cpog ON cpog.open_graph_id = og.id 
                WHERE og.id = :ogId ";
        $res = $dbm->findOne($stm, ['ogId' => $id]);
        // var_dump($id);
        // var_dump($res);//exit;
        return $res['count_cpog'] == 0 ? true : false;
    }

    public function createCode()
    {
        $videoKey = $this->getContainer()->getSession()->get('visitorCode').'_'.BasicUtils::generateRandomString(12);
        return $videoKey;
    }

    public function getGridDataFilteredQuery($filter)
    {
        $whereClause = $this->createWhereClauseFromFilter($filter && isset($filter['conditions']) ? $filter['conditions'] : null);
        return array(
            'statement' => "SELECT * FROM (SELECT maintable.id, maintable.title, maintable.description, (CASE WHEN cpog.id IS NULL THEN 1 ELSE 0 END) AS 'deletable'
                            FROM open_graph maintable 
                            LEFT JOIN custom_page_open_graph cpog ON cpog.open_graph_id = maintable.id
                            WHERE website = '".App::getWebsite()."') table0
                            ".$whereClause['whereStr']." ",
            'params' => $whereClause['params']
        );
    }

    public function removeOpenGraphImageHeaders($openGraphId)
    {
        $dbm = $this->getDbManager();
        $stm = "DELETE FROM open_graph_image_header WHERE open_graph_id = :openGraphId ";
        $dbm->execute($stm, ['openGraphId' => $openGraphId]);
        return true;
    }
}
