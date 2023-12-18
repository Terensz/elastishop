<?php
namespace framework\packages\VisitorPackage\service;

use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\packages\VisitorPackage\entity\Referer;
use framework\packages\VisitorPackage\entity\Visit;

class VisitorService extends Kernel
{
    const MOST_FREQUENT_KEYWORDS_LIMIT = 10;
    
    public function __construct()
    {

    }
}
