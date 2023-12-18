<?php
namespace framework\packages\SeoPackage\service;

use framework\kernel\component\Kernel;
use framework\component\entity\Route;
use framework\component\exception\ElastiException;
use framework\packages\FrameworkPackage\repository\CustomPageRepository;

class DescriptionBuilder extends Kernel
{
    private $description;
    private $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
        $this->build();
    }

    public function createDescriptionFromConfig()
    {
        $descriptionParts = array();
        if (isset($companyData['description'])) {
            foreach ($companyData['description'] as $descriptionPart) {
                $descriptionParts[] = $descriptionPart;
            }
        }
        sort($descriptionParts);
        $this->description = implode(', ', $descriptionParts);
    }

    public function createDescriptionFromDatabase()
    {
        try {
            $this->getContainer()->setService('FrameworkPackage/service/CustomPageService');
            $customPageService = $this->getContainer()->getService('CustomPageService');
            $this->description = $customPageService->getCustomPageDescription($this->route->getName());
            if (empty($this->description)) {
                $this->createDescriptionFromConfig();
            }
        } catch(ElastiException $e) {
            if ($e->getCode() == 1660) {
                return true;
                // dump($e);exit;
            }
        }
    }

    public function build()
    {
        $dbm = $this->getContainer()->getKernelObject('DbManager');
        if ($dbm->getConnection() && $dbm->tableExists('searched_keyword')) {
            $this->createDescriptionFromDatabase();
        } else {
            $this->createDescriptionFromConfig();
        }
    }

    public function getDescription()
    {
        return $this->description;
    }
}
