<?php
namespace framework\packages\UserPackage\repository;

use framework\component\parent\DbRepository;

class TemporaryPersonRepository extends DbRepository
{
    public function isDeletable($id)
    {
        return true;
    }
}
