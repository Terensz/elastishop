<?php
namespace framework\packages\FinancePackage\repository;

use framework\component\parent\DbRepository;
use framework\packages\UserPackage\entity\Person;

class InvoiceHeaderRepository extends DbRepository
{
    /**
     * Never set this anything else!
    */
    public function isDeletable($id)
    {
        return false;
    }

    public function getGridDataFilteredQuery($filter)
    {
        $whereClause = $this->createWhereClauseFromFilter($filter ? $filter['conditions'] : null);
        return array(
            'statement' => "SELECT * FROM (SELECT maintable.id, maintable.invoice_number, maintable.order_number as shipment_identifier, CONCAT(sum(items.item_net),' ',maintable.currency) as total_net, maintable.corrected_invoice_number as corrected_invoice_number, year_of_issue, buyer_name, tax_office_comm_status
                            FROM ".$this->getTableName()." maintable
                            LEFT JOIN invoice_item items ON maintable.id = items.invoice_header_id
                            GROUP BY maintable.id) table0
                            ".$whereClause['whereStr']." ",
            'params' => $whereClause['params']
        );
    }
}
