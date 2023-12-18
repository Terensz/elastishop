<?php
namespace framework\packages\ArticlePackage\controller;

use framework\component\parent\PageController;
// use framework\packages\ArticlePackage\entity\Article;

class ArticleController extends PageController
{
    /**
    * Route: [name: article_view, paramChain: /article/{slug}]
    */
    public function articleViewAction($slug)
    {
        // dump('articleViewAction');
        $this->setService('ArticlePackage/loader/Loader');
        $repo = $this->getService('ArticleRepository');

        $articles = $repo->findByInCollection(
            $repo->collectAll(), ['conditions' => [['key' => 'slug', 'value' => $slug]]], 'result'
        );
        // dump($slug);
        // dump($articles);
        // dump($repo->collectAll());exit;

        $title = $articles ? $articles[0]->getTitle() : trans('article.not.found.title');

        // dump($articles);
        // dump($title);

        return $this->renderPage(array('container' => $this->getContainer()), array(), null, $title);
    }
}
