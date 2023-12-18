<?php
namespace framework\packages\SeoPackage\repository;

use framework\component\parent\DbRepository;

class SearchedKeywordRepository extends DbRepository
{
    public function __construct()
    {

    }

    public function findMostFrequents($limit = 10)
    {
        $stm = "SELECT name, count, search_string FROM(
                    SELECT name, count(id) as count, GROUP_CONCAT(DISTINCT search_string SEPARATOR '[separator]') as search_string
                    FROM searched_keyword GROUP BY name
                ) as keywords 
                ORDER BY keywords.count DESC, name ASC
                LIMIT ".$limit;
        $dbm = $this->getDbManager();
        $result = [
            'result' => $dbm->findAll($stm, [
                // ':periodStartDate' => $periodDates['start'],
                // ':periodEndDate' => $periodDates['end']
            ])
        ];

        // dump($result);
        return $result;
    }
}
