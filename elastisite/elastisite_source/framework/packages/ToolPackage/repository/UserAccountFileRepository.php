<?php
namespace framework\packages\ToolPackage\repository;

use framework\component\parent\DbRepository;
use framework\packages\ToolPackage\entity\UserAccountFile;

class UserAccountFileRepository extends DbRepository
{
    public function __construct()
    {
        $this->getContainer()->wireService('ToolPackage/entity/UserAccountFile');
    }

    // public function store($userAccountFile)
    // {
    //     return $this->insert($userAccountFile);
    // }

    // public function insert($userAccountFile)
    // {
    //     $stm = "INSERT INTO user_account_file (visitor_id, user_account_id, file_id)
    //                 VALUES(:visitorId, :userAccountId, :fileId)";
    //     $params = [
    //         'visitorId' => $userAccountFile->getVisitorId(),
    //         'userAccountId' => $userAccountFile->getUserAccount()->getId(),
    //         'fileId' => $userAccountFile->getFileId()
    //     ];
    //     $id = $this->getDbManager()->execute($stm, $params);
    //     return $id;
    // }
}
