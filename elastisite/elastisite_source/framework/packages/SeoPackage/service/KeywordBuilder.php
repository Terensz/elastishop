<?php
namespace framework\packages\SeoPackage\service;

use App;
use framework\kernel\component\Kernel;
use framework\packages\SeoPackage\repository\SearchedKeywordRepository;
use framework\component\entity\Route;
use framework\component\exception\ElastiException;

class KeywordBuilder extends Kernel
{
    private $route;
    private $keywords;

    public function __construct(Route $route)
    {
        $this->route = $route;
        $this->build();
    }

    public function add($keyword)
    {
        $this->keywords[] = $keyword;
    }

    public function createKeywordsFromConfig()
    {
        $companyData = $this->getContainer()->getConfig()->getCompanyData();
        if (isset($companyData['name'])) {
            $this->keywords[] = $companyData['name'];
        }
        
        if (isset($companyData['keywords'])) {
            foreach ($companyData['keywords'] as $companyKeyword) {
                $this->keywords[] =  $companyKeyword;
            }
        }
    }

    public function createKeywordsFromDatabase()
    {
        try {
            $this->createKeywordsFromSearched();
            $this->createKeywordsFromDefaultPage();
            $this->createKeywordsFromCustomPage();
        } catch(ElastiException $e) {
            if ($e->getCode() == 1660) {
                return true;
                // dump($e);exit;
            }
        }
    }

    public function createKeywordsFromSearched()
    {
        $this->getContainer()->wireService('SeoPackage/repository/SearchedKeywordRepository');
        $keywordRepo = new SearchedKeywordRepository();
        $keywords = $keywordRepo->findMostFrequents();
        if ($keywords && isset($keywords['result'])) {
            foreach ($keywords['result'] as $keyword) {
                $this->keywords[] =  $keyword['name'];
            }
        }
    }

    public function createKeywordsFromDefaultPage()
    {
        $this->setService('SeoPackage/repository/PageKeywordRepository');
        $pageKeywordRepo = $this->getService('PageKeywordRepository');
        $pageKeywords = $pageKeywordRepo->findBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'route_name', 'value' => 'reserved_default_route']
        ]]);

        if ($pageKeywords && is_array($pageKeywords)) {
            foreach ($pageKeywords as $pageKeyword) {
                $this->keywords[] = $pageKeyword->getName();
            }
        }
    }

    public function createKeywordsFromCustomPage()
    {
        $this->setService('SeoPackage/repository/PageKeywordRepository');
        $pageKeywordRepo = $this->getService('PageKeywordRepository');
        $pageKeywords = $pageKeywordRepo->findBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'route_name', 'value' => $this->route->getName()]
        ]]);

        if ($pageKeywords && is_array($pageKeywords)) {
            foreach ($pageKeywords as $pageKeyword) {
                $this->keywords[] = $pageKeyword->getName();
            }
        }
    }

    public function build()
    {
        $this->add($this->route->getTitle());

        $this->createKeywordsFromConfig();

        $dbm = $this->getContainer()->getKernelObject('DbManager');
        if ($dbm->getConnection() && $dbm->tableExists('searched_keyword')) {
            $this->createKeywordsFromDatabase();
        }
    }

    public function getKeywords()
    {        
        $keywords = $this->keywords;
        $collator = new \Collator('hu_HU');
        $collator->sort($keywords);
        return implode(', ', $keywords);
    }
}
