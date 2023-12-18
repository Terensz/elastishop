<?php
namespace framework\packages\DevPackage\controller;

use framework\component\parent\PageController;
use framework\packages\ArticlePackage\entity\Article;

class ArticleDevController extends PageController
{
    public function articleDevAddAction()
    {
        $this->setService('ArticlePackage/loader/Loader');
        $artRepo = $this->getService('ArticleRepository');
        $artRepo->setFilePath($artRepo->getPathBase().'/homepage.txt');
        $artRepo->removeAllObjects();

        $article = new Article();
        $article->setTeaserType($article::TEASER_TYPE_HARD_CODED);
        $article->setTitle('Elindult az ElastiSite!');
        $article->setSlug('start');
        $article->setTeaser('Blabla. Bla. Blab alabla.');
        $article->setHardCodedSlug('start');

        $artRepo->store($article);
        dump($article);
    }
}
