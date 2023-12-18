<?php
namespace framework\packages\VideoPackage\repository;

use framework\component\parent\DbRepository;
use framework\kernel\utility\BasicUtils;

class VideoRepository extends DbRepository
{
    public function __construct()
    {

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
            'statement' => "SELECT * FROM (SELECT maintable.id, maintable.title, maintable.description
                            -- , maintable.status
                            FROM video maintable
                            ) table0
                            ".$whereClause['whereStr']." ",
            'params' => $whereClause['params']
        );
    }
}
