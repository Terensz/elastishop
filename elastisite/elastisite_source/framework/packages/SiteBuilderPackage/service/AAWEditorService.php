<?php
namespace framework\packages\SiteBuilderPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\SiteBuilderPackage\entity\Article;
use framework\packages\SiteBuilderPackage\entity\ArticleParagraph;
use framework\packages\SiteBuilderPackage\repository\ArticleRepository;

class AAWEditorService extends Service
{
    public static $articles;

    public static $articleRepository;

    public static function getArticle(int $articleId = null) : Article
    {
        if ($articleId) {
            $article = self::getArticleRepository()->find($articleId);
            if ($article) {
                return $article;
            } else {
                throw new \Exception('Missing article: '.$articleId);
            }
        }

        $routeName = App::getContainer()->getRouting()->getPageRoute()->getName();
        $articleUniqueCode = $routeName;

        if (isset(self::$articles[$articleUniqueCode])) {
            return self::$articles[$articleUniqueCode];
        }

        $article = self::getArticleRepository()->findOneBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'route_name', 'value' => $routeName]
        ]]);

        if (!$article) {
            $article = new Article();
            $article->setRouteName($routeName);
            $article = self::getArticleRepository()->store($article);
        }
        self::$articles[$articleUniqueCode] = $article;

        return $article;
    }

    public static function getArticleRepository() : ArticleRepository
    {
        if (!self::$articleRepository) {
            App::getContainer()->wireService('SiteBuilderPackage/repository/ArticleRepository');
            self::$articleRepository = new ArticleRepository();
        }

        return self::$articleRepository;
    }

    public static function addArticleParagraph(Article $article) : ArticleParagraph
    {
        App::getContainer()->wireService('SiteBuilderPackage/entity/ArticleParagraph');
        $paragraph = new ArticleParagraph();
        $paragraph->setArticle($article);
        $paragraph->setSequenceNumber($paragraph->getRepository()->getNewSequenceNumber($article->getId()));
        $paragraph = $paragraph->getRepository()->store($paragraph);

        return $paragraph;
    }    
}
