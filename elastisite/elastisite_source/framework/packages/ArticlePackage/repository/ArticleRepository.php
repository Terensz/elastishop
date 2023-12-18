<?php
namespace framework\packages\ArticlePackage\repository;

use App;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\component\parent\FileBasedStorageRepository;
use framework\component\entity\Route;

class ArticleRepository extends FileBasedStorageRepository
{
    public function getPathBase()
    {
        $pathBase = rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/projects/'.App::getWebProject().'/file_based_storage/articles/'.$this->getContainer()->getSession()->getLocale();
        dump($pathBase);exit;

        return $pathBase;
    }

    public function initRepository($debug = false)
    {
        // dump($route);
        dump($debug);exit;
        $routeArray = $this->getContainer()->getKernelObject('RoutingHelper')->getRoute($this->getRouting()->getPageRoute()->getName());
        $route = new Route();
        $route->set($routeArray);
        $filePath = $this->getPathBase().'/'.$route->getName().'.txt';

        if (!is_file($filePath)) {
            $file = fopen($filePath, "w");
            fwrite($file, "");
            fclose($file);
        }
        $this->filePath = $filePath;
        if ($debug) {
            dump('$filePath: '.$filePath);
            dump('$this->filePath: '.$this->filePath);
        }
    }

    public function find($id)
    {
        if (!$id) {
            return null;
        }
        $this->initRepository('find');
        $articles = $this->findBy(['conditions' => [['key' => 'id', 'value' => $id]], 'maxResults' => 1], 'result', null);
        return (is_array($articles) && count($articles) > 0) ? $articles[0] : null;
    }

    public function findOneBy($filter = array())
    {
        $this->initRepository('findOneBy');
        $filter['maxResults'] = 1;
        return $this->findBy($filter, 'result');
    }

    public function findAll($debug = null)
    {
        $this->initRepository('findAll');
        return parent::findAll();
    }

    public function removeBy($properties)
    {
        $this->initRepository('removeBy');
        return parent::removeBy($properties);
    }

    public function getArticleSchemaData($id) {
        $this->initRepository('getArticleSchemaData');
        $article = $this->find($id);
        return $article == array() ? null : $article[0];
    }

    public function storeArticleSchemaData($article) {
        $this->initRepository('storeArticleSchemaData');
        // $article = new Article();
        // $article = $this->assembleEntity($article, $data['Article']);
        // dump($article);exit;
        $this->store($article);
    }

    public function store($object)
    {
        $this->initRepository('store');
        $object->setPermission($this->getContainer()->getUrl()->getPageRoute()->getPermission());
        if (!$object->getSlug()) {
            $object->setSlug($this->makeSlug($object->getTitle()));
        }
        parent::store($object);
    }

    public function makeSlug($title)
    {
        $slug = BasicUtils::slugify($title);
        $findSlug = $this->findByInCollection(
            $this->collectAll(), 
            ['conditions' => [['key' => 'slug', 'value' => $slug]], 'maxResults' => 1], 
            'result');
        $this->initRepository('makeSlug');
        if ($findSlug == array()) {
            return $slug;
        } else {
            $lastChar = substr($slug, -1);
            if (ctype_digit($lastChar)) {
                $lastChar++;
                $slug = substr($slug, 0, -1).$lastChar;
            } else {
                $slug = $slug.'2';
            }
        }
        return $this->makeSlug($slug);
    }

    public function collectAll()
    {
        $articles = array();
        $files = FileHandler::getAllFileNames($this->getPathBase().'');
        // dump($files);
        foreach ($files as $file) {
            $this->setFilePath($this->getPathBase().'/'.$file);
            // dump($this->getFilePath());
            $storedArticles = parent::findAll(array('path' => $this->getFilePath()));
            $addArticles = array();
            if (!$storedArticles) {
                continue;
            }
            foreach ($storedArticles as $storedArticle) {
                $storedArticle->setMainRoute(BasicUtils::explodeAndRemoveElement($file, '.', 'last'));
                $addArticles[] = $storedArticle;
            }
            $articles = array_merge($articles, $addArticles);
            $this->setFilePath(null);
        }
        // dump($articles); exit;
        return $articles;
    }

    public function getHardCodedArticles()
    {
        $hardCodedArticles = array();
        foreach ($this->getContainer()->getFullRouteMap() as $rawRoute) {
            $rawRouteParts = explode('_', $rawRoute['name']);
            if ($rawRouteParts[0] == 'hardCodedArticle') {
                $route = new Route();
                $route->set($rawRoute);
                $hardCodedArticles[] = $route;
            }
        }
        return $hardCodedArticles;
    }

    public function search($term)
    {
        $returnArticles = array();
        $articles = $this->collectAll();
        foreach ($articles as $article) {
            $termInTitle = strpos(strtolower($article->getTitle()), strtolower($term));
            $termInTeaser = strpos(strtolower($article->getTeaser()), strtolower($term));
            $termInBody = strpos(strtolower($article->getBody()), strtolower($term));

            if ($termInTitle !== false || $termInTeaser !== false || $termInBody !== false) {
                $returnArticles[] = $article;
            }
        }
        return $returnArticles;
    }
}
