<?php
namespace framework\packages\WordClearingPackage\repository;

use framework\component\parent\DbRepository;

class WordExplanationRepository extends DbRepository
{
    public function isDeletable($id)
    {
        return true;
    }
}
