<?php
namespace framework\packages\UserPackage\repository;

use framework\component\parent\DbRepository;
use framework\packages\WebshopPackage\entity\Shipment;

class TemporaryAccountRepository extends DbRepository
{
    public function isDeletable($id)
    {
        // dump($this->notBelongsToShipment($id));
        // dump($this->notBelongsToTemporaryPerson($id));
        if ($this->notBelongsToShipment($id)) {
            return true;
        }
        
        return false;
    }

    public function remove($id)
    {
        $pdo = $this->getContainer()->getKernelObject('DbManager')->getConnection();
        $pdo->beginTransaction();
        try {
            // dump($pdo); exit;
            $temporaryAccount = $this->find($id);
            if ($temporaryAccount) {
                // dump($temporaryAccount);//exit;
                // dump($this->isDeletable($temporaryAccount->getId()));
                // dump($this->isDeletable($temporaryAccount->getId())); //exit;
                if ($this->isDeletable($temporaryAccount->getId())) {
                    $temporaryPerson = $temporaryAccount->getTemporaryPerson();
                    $temporaryAddress = $temporaryPerson->getAddress();
                    if ($temporaryAddress) {
                        $temporaryAddress->getRepository()->remove($temporaryAddress->getId());
                    }
                    $temporaryPerson->setAddress(null);
                    $temporaryPerson->getRepository()->remove($temporaryPerson->getId());
                    $temporaryAccount->setTemporaryPerson(null);
                    parent::remove($temporaryAccount->getId());
                }
            }
			$pdo->commit();
		} catch(\Exception $e) {
			$pdo->rollback();
            dump($e); exit;
		}
        // $temporaryAccount = $this->find($id);
        // if ($temporaryAccount) {
        //     if ($this->isDeletable($temporaryAccount->getId())) {
        //         $temporaryPerson = $temporaryAccount->getTemporaryPerson();
        //         $temporaryAddress = $temporaryPerson->getAddress();
        //         $temporaryAddress->getRepository()->remove($temporaryAddress->getId());
        //         $temporaryPerson->setAddress(null);
        //         $temporaryPerson->getRepository()->remove($temporaryPerson->getId());
        //         $temporaryAccount->setTemporaryPerson(null);
        //         parent::remove($temporaryAccount->getId());
        //     }
        // }
    }

    // public function findNotUsed()
    // {
    //     $this->getContainer()->setService('WebshopPackage/service/WebshopService');
    //     $webshopService = $this->getContainer()->getService('WebshopService');

    //     $dbm = $this->getDbManager();
    //     $stm = "SELECT ta.id 
    //             FROM temporary_account ta 
    //             INNER JOIN temporary_person tp ON tp.temporary_account_id = ta.id
    //             LEFT JOIN shipment s ON s.temporary_account_id = ta.id
    //             WHERE ta.visitor_code = :visitorCode AND s.id IS NULL ";
    //     $result = $dbm->findOne($stm, ['visitorCode' => $this->getContainer()->getSession()->get('visitorCode')]);
    //     // dump($this->find($result['id']));
    //     return $result ? $this->find($result['id']) : null;
    // }

    // public function notBelongsToOrder($id)
    // {
    //     // $this->getContainer()->setService('WebshopPackage/service/WebshopService');
    //     // $webshopService = $this->getContainer()->getService('WebshopService');
    //     $dbm = $this->getDbManager();
    //     $stm = "SELECT s.id as shipment_id
    //             FROM temporary_account ta 
    //             INNER JOIN shipment s ON s.temporary_account_id = ta.id
    //             WHERE ta.id = :id ";
    //     $result = $dbm->findOne($stm, ['id' => $id]);
    //     return $result ? false : true;
    // }

    public function notBelongsToShipment($id)
    {
        $dbm = $this->getDbManager();
        $stm = "SELECT count(id) as id_count FROM shipment s WHERE s.temporary_account_id = :temporary_account_id ";
        $result = $dbm->findOne($stm, ['temporary_account_id' => $id]);
        // dump($result);
        return $result['id_count'] == 0 ? true : false;
    }

    public function notBelongsToTemporaryPerson($id)
    {
        $dbm = $this->getDbManager();
        $stm = "SELECT count(id) as id_count FROM temporary_person tp WHERE tp.temporary_account_id = :temporary_account_id ";
        $result = $dbm->findOne($stm, ['temporary_account_id' => $id]);

        return $result ? false : true;
    }

    public function exclusivelyBelongsToShipment($id, $shipmentId)
    {
        $dbm = $this->getDbManager();
        $stm = "SELECT count(id) as id_count FROM shipment s WHERE s.temporary_account_id = :temporary_account_id AND s.id <> :shipment_id ";
        $result = $dbm->findOne($stm, [
            'temporary_account_id' => $id, 
            'shipment_id' => $shipmentId
        ]);

        return $result ? false : true;
    }
}
