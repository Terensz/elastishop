<?php
namespace framework\packages\SurveyPackage\repository;

use framework\component\parent\DbRepository;
use framework\kernel\utility\BasicUtils;

class SurveyRepository extends DbRepository
{
    public function isDeletable($id)
    {
        return false;
    }

    public function getGridDataFilteredQuery($filter)
    {
        $whereClause = $this->createWhereClauseFromFilter($filter ? $filter['conditions'] : null);
        return array(
            'statement' => "SELECT * FROM (SELECT maintable.id, maintable.title, maintable.created_at, maintable.status
                            FROM ".$this->getTableName()." maintable
                            LEFT JOIN invoice_item items ON maintable.id = items.invoice_header_id
                            GROUP BY maintable.id) table0
                            ".$whereClause['whereStr']." ",
            'params' => $whereClause['params']
        );
    }

    public function isDisabled($id)
    {
        $dbm = $this->getDbManager();
        $stm = "SELECT count(s.id) as id_count
        FROM survey s
        INNER JOIN survey_completion sc on sc.survey_id = s.id
        WHERE s.id = :survey_id
        ";
        $result = $dbm->findOne($stm, ['survey_id' => $id])['id_count'];
        // dump($result);exit;

        return $result > 0 ? true : false;
    }

    public function isStorable($id)
    {
        return !$this->isDisabled($id);
    }
}
