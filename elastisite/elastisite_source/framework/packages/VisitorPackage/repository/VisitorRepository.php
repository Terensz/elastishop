<?php
namespace framework\packages\VisitorPackage\repository;

use framework\component\parent\DbRepository;
use framework\packages\UserPackage\entity\Person;

class VisitorRepository extends DbRepository
{
    public function isCode($code)
    {
        $stm = "SELECT id FROM visitor WHERE code = '".$code."'";
        $dbm = $this->getDbManager();
        $ret = $dbm->findOne($stm);
        return $ret;
    }

    // public function store($visitor)
    // {
    //     if (!$this->isCode($visitor->getCode())) {
    //         $stm = "INSERT INTO visitor (user_account_id, code, first_visit)
    //                     VALUES(:user_account_id, :code, :first_visit)";
    //         $params = [
    //             'user_account_id' => $visitor->getUserAccount()->getId(),
    //             'code' => $visitor->getCode(),
    //             'first_visit' => $this->getCurrentTimestamp()
    //         ];
    //         $id = $this->getDbManager()->execute($stm, $params);
    //         return $id;    
    //     }
    // }
}
