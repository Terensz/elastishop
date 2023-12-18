<?php
namespace framework\packages\UserPackage\repository;

use framework\component\parent\DbRepository;

class AddressRepository extends DbRepository
{
    public function isAvailable($address)
    {
        $availableCountries = $this->getContainer()->getConfig()->getProjectData('availableCountries');
        if ($availableCountries && is_array($availableCountries)) {
            foreach ($availableCountries as $availableCountry) {
                if ($availableCountry == $address->getCountry()->getAlphaTwo()) {
                    return true;
                }
            }
            return false;
        } else {
            return true;
        }
    }

    public function isEditable($id, $currentTemporaryPersonId = null)
    {
        return true;
        // $dbm = $this->getDbManager();
        // $stm = "SELECT COUNT(tp.id) + COUNT(s.id) as 'bound_count'
        // FROM address a 
        // LEFT JOIN temporary_person tp ON tp.organization_id = o.id ".($currentTemporaryPersonId ? " AND tp.id <> :current_temporary_person_id " : "")."
        // LEFT JOIN shipment s ON s.organization_id = o.id 
        // WHERE o.id = :organization_id 
        // ";

        // $params = [];
        // if ($currentTemporaryPersonId) {
        //     $params['current_temporary_person_id'] = $currentTemporaryPersonId;
        // }
        // $params['organization_id'] = $id;
        // $result = $dbm->findOne($stm, $params)['bound_count'];
        
        // return (int)$result === 0;
    }
}
