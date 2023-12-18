<?php
namespace framework\packages\SurveyPackage\repository;

use framework\component\parent\DbRepository;

class SurveyOptionRepository extends DbRepository
{
    /**
     * @todo: ElastiSite does not handle this feature well.
    */
    public function cleanUpOrphans()
    {
        return false;
    }
}
