<?php
namespace framework\kernel\security\repository;

use framework\component\parent\DbRepository;

class SecurityEventRepository extends DbRepository
{
    public function __construct()
    {

    }

    public function getGridDataFilteredQuery($filter)
    {
        $whereClause = $this->createWhereClauseFromFilter($filter && isset($filter['conditions']) ? $filter['conditions'] : null);
        return array(
            'statement' => "SELECT * FROM (SELECT maintable.id, maintable.country_code, maintable.city, maintable.event_type, maintable.ip_address, maintable.created_at
                            FROM security_event maintable
                            ) table0
                            ".$whereClause['whereStr']." ",
            'params' => $whereClause['params']
        );
    }
}
