<?php
namespace framework\kernel\routing\entity;

use framework\component\entity\Route;
use framework\kernel\component\Kernel;

class Routing extends Kernel
{
    private $pageRoute;
    private $actualRoute;

    public function getPageRoute() : ? Route
    {
        return $this->pageRoute;
    }

    public function setPageRoute($pageRoute)
    {
        $this->pageRoute = $pageRoute;
    }

    public function getActualRoute() : ? Route
    {
        return $this->actualRoute;
    }

    public function setActualRoute($actualRoute)
    {
        $this->actualRoute = $actualRoute;
    }
}
