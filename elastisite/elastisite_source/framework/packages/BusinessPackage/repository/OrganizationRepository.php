<?php
namespace framework\packages\BusinessPackage\repository;

use App;
use framework\component\parent\DbRepository;
use framework\packages\UserPackage\entity\Person;
use framework\packages\UserPackage\entity\User;

class OrganizationRepository extends DbRepository
{
    public function isEditable($id, $currentTemporaryPersonId = null)
    {
        $dbm = $this->getDbManager();
        $stm = "SELECT COUNT(tp.id) as 'bound_count'
        FROM organization o 
        LEFT JOIN temporary_person tp ON tp.organization_id = o.id ".($currentTemporaryPersonId ? " AND tp.id <> :current_temporary_person_id " : "")."
        WHERE o.id = :organization_id 
        ";

        $params = [];
        if ($currentTemporaryPersonId) {
            $params['current_temporary_person_id'] = $currentTemporaryPersonId;
        }
        $params['organization_id'] = $id;
        $result = $dbm->findOne($stm, $params)['bound_count'];
        
        return (int)$result === 0;
    }
}
