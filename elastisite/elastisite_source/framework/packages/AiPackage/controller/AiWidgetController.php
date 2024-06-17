<?php
namespace framework\packages\AiPackage\controller;

use framework\kernel\utility\BasicUtils;
use framework\component\parent\WidgetController;
use framework\packages\ArticlePackage\entity\Article;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\WordClearingPackage\service\WordExplanationService;

class AiWidgetController extends WidgetController
{
    /**
    * Route: [name: aiWidget, paramChain: /aiWidget]
    */
    public function aiWidgetAction()
    {
        // dump('articleWidgetAction');
        $this->setService('ArticlePackage/loader/Loader');
        $repo = $this->getService('ArticleRepository');
        $subRouteRequest = $this->getContainer()->getUrl()->getSubRouteRequest();
        $articles = $repo->findByInCollection($repo->collectAll(), ['conditions' => [
                ['key' => 'slug', 'value' => $subRouteRequest]
            ]], 'result'
        );
        if (!$articles || $articles == array()) {
            $article = new Article();
            $article->setTitle(trans('article.not.found.title'));
            $body = '';
            $viewPath = 'framework/packages/ArticlePackage/view/widget/ArticleWidget/notFound.php';
        } else {
            $article = $articles[0];
            if ($article->getHardCodedSlug()) {
                return $this->callRoute('hardCodedArticle/'.$article->getHardCodedSlug());
            } else {
                $viewPath = 'framework/packages/ArticlePackage/view/widget/ArticleWidget/article.php';
            }
        }

        $response = [
            'view' => $this->renderWidget('ArticleWidget', $viewPath, [
                'container' => $this->getContainer(),
                'article' => $article
            ]),
            'data' => [
                'title' => $article->getTitle()
            ]
        ];
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: teaser_widget, paramChain: /teaser/widget]
    */
    public function teaserWidgetAction()
    {
        // dump('teaserWidgetAction'); // bingo!
        $this->setService('ArticlePackage/loader/Loader');
        $this->wireService('WordClearingPackage/service/WordExplanationService');
        $wordExplanationService = new WordExplanationService();
        $repo = $this->getService('ArticleRepository');
        $articles = $repo->findAll();
        $articles = $repo->sortBy($articles, ['position' => 'ASC', 'createdAt' => 'DESC']);
        if ($articles) {
            for ($i = 0; $i < count($articles); $i++) {
                $article = $this->tagsToHtml($articles[$i]);
                $article->setTeaser($wordExplanationService->processWordExplanations($article->getTeaser()));
                $articles[$i] = $article;
            }
        }

        // dump($articles);exit;

        $user = $this->getContainer()->getUser();
        $viewPath = 'framework/packages/ArticlePackage/view/widget/TeaserWidget/widget.php';

        $req = $this->getContainer()->getRequest();

        $response = [
            'view' => $this->renderWidget('TeaserWidget', $viewPath, [
                'container' => $this->getContainer(),
                'user' => $user,
                'articles' => $articles
            ]),
            'data' => []
        ];
        return $this->widgetResponse($response);
    }

    public function tagsToHtml($article)
    {
        $article->setTeaser($this->tagsToHtmlLinks($article->getTeaser()));
        $article->setBody($this->tagsToHtmlLinks($article->getBody()));
        return $article;
    }

    public function tagsToHtmlLinks($text)
    {
        $links = BasicUtils::getContentBetween($text, '[link]', '[/link]');
        foreach ($links as $link) {
            $config = [];
            $href = null;
            $title = null;
            $link = trim($link);
            $parts = explode('@', $link);
            $configPos = strpos($parts[0], '{');
            if ($configPos !== false) {
                $configStr = trim($parts[0], '{');
                $configStr = trim($configStr, '}');
                $configArray = explode(',', $configStr);
                foreach ($configArray as $configRow) {
                    $configParts = explode(':', $configRow);
                    $config[$configParts[0]] = $configParts[1];
                    // dump($config);exit;
                }
                $href = $parts[1];
                $title = $parts[2];
            } else {
                $href = $parts[0];
                $title = $parts[1];
            }

            $newLink = '<a '.((isset($config['ajax']) && $config['ajax'] == 'on') ? 'class="ajaxCallerLink" ' : '').'href="'.$href.'">'.$title.'</a>';
            $text = str_replace('[link]'.$link.'[/link]', $newLink, $text);
        }
        return $text;
    }

    /**
    * Route: [name: article_search_widget, paramChain: /article_search_widget]
    */
    public function articleSearchWidgetAction()
    {
        $this->setService('ArticlePackage/loader/Loader');
        $repo = $this->getService('ArticleRepository');
        $articles = null;
        $submitted = false;

        $searchReq = $this->getContainer()->getRequest()->get('ArticlePackage_articleSearch_mixed');
        if ($searchReq) {
            if (strlen($searchReq) < 4) {
                $this->getContainer()->addSystemMessage('search.min.digits', 'error', 'ArticlePackage_articleSearch');
            }
            else {
                $submitted = true;
                $articles = $repo->search($searchReq);
            }
        }

        $messages = $this->getSystemMessages(['subject' => 'ArticlePackage_articleSearch']);
        $message = (isset($messages[0])) ? $messages[0] : null;

        $viewPath = 'framework/packages/ArticlePackage/view/widget/ArticleSearchWidget/foundArticleList.php';
        $response = [
            'view' => $this->renderWidget('ArticleSearchWidget', $viewPath, [
                'container' => $this->getContainer(),
                'articles' => $articles,
                'message' => $message,
                'submitted' => $submitted
            ]),
            'data' => []
        ];
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_article_edit, paramChain: /admin/article/edit]
    */
    public function adminArticleEditAction()
    {
        $this->wireService('ArticlePackage/entity/Article');
        $container = $this->getContainer();
        $this->setService('ArticlePackage/loader/Loader');
        $repo = $this->getService('ArticleRepository');
        $route = $container->getKernelObject('RoutingHelper')->searchRoute($container->getUrl()->getParamChain());
        $repo->setFilePath($route->getName());
        $this->wireService('FormPackage/service/FormBuilder');

        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('ArticlePackage');
        $formBuilder->setSubject('article');
        $formBuilder->setPrimaryKeyValue($this->getContainer()->getRequest()->get('articleId'));
        $formBuilder->addExternalPost('articleId');
        $form = $formBuilder->createForm();
        $viewPath = 'framework/packages/ArticlePackage/view/widget/TeaserWidget/articleEdit/form.php';
        
        $viewData = [
            'container' => $this->getContainer(),
            'form' => $form,
            'article' => new Article(),
            'articleId' => $this->getContainer()->getRequest()->get('articleId'),
            'hardCodedArticles' => $repo->getHardCodedArticles()
        ];

        $response = [
            'view' => $this->renderWidget('TeaserWidget', $viewPath, $viewData, true),
            'data' => [
                'formIsValid' => $form->isValid(),
                'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll()
            ]
        ];
        // dump('$response utan');

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_article_move, paramChain: /admin/article/move]
    */
    public function adminArticleMoveAction()
    {
        $id = $this->getContainer()->getRequest()->get('articleId');
        $direction = $this->getContainer()->getRequest()->get('direction');

        $this->setService('ArticlePackage/loader/Loader');
        $repo = $this->getService('ArticleRepository');
        $articles = $repo->findAll();
        $articles = $repo->sortBy($articles, ['position' => 'ASC', 'createdAt' => 'DESC'], false);

        for ($i = 0; $i < count($articles); $i++) {
            if ($articles[$i]->getId() == $id) {
                $article = $articles[$i];
                $oldPosition = $article->getPosition();
                if ($direction == 'up') {
                    $newPosition = $article->getPosition() - 1;
                } else {
                    $newPosition = $article->getPosition() + 1;
                }
                $changeWithArray = $articles = $repo->findBy(['conditions' => [['key' => 'position', 'value' => $newPosition]]]);
                foreach ($changeWithArray as $changeWith) {
                    $changeWith->setPosition($oldPosition);
                    $repo->store($changeWith);
                }
                $article->setPosition($newPosition);
                $repo->store($article);
            }
        }

        // $article2 = $repo->find($id);

        $response = [
            'view' => ''
            ,
            'data' => [
                'oldPosition' => $oldPosition,
                'newPosition' => $newPosition,
                'articleId' => $this->getContainer()->getRequest()->get('articleId')
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_article_delete, paramChain: /admin/article/delete]
    */
    public function adminArticleDeleteAction()
    {
        $this->setService('ArticlePackage/loader/Loader');
        $repo = $this->getService('ArticleRepository');
        // dump($this->getContainer()->getUrl());
        $repo->removeBy(['id' => $this->getContainer()->getRequest()->get('articleId')]);

        $response = [
            'view' => ''
            ,
            'data' => [
                'articleId' => $this->getContainer()->getRequest()->get('articleId')
            ]
        ];

        return $this->widgetResponse($response);
    }
}
