<?php
namespace framework\packages\SiteBuilderPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\SiteBuilderPackage\service\AAWEditorService;
use framework\packages\SiteBuilderPackage\entity\Article;
use framework\packages\SiteBuilderPackage\entity\ArticleParagraph;

/**
 * Article
 * ArticleParagraph
 * ArticleColumn
 * ArticleBlock
 * 
 * ArticleUnit
 * ArticleText
 * ArticleImage
*/

class AdvancedArticleWidgetController extends WidgetController
{
    public $aawEditorService;

    public function __construct()
    {
        App::getContainer()->wireService('SiteBuilderPackage/service/AAWEditorService');
    }

    public function getAAWEditorService()
    {
        if ($this->aawEditorService) {
            return $this->aawEditorService;
        }
        $this->aawEditorService = new AAWEditorService();

        return $this->aawEditorService;
    }

    /**
    * Route: [name: widget_AdvancedArticleWidget, paramChain: /widget/AdvancedArticleWidget]
    */
    public function advancedArticleWidgetAction()
    {
        return $this->getAdvancedArticleWidgetContent();
    }

    /**
    * Route: [name: widget_WrappedAdvancedArticleWidget, paramChain: /widget/WrappedAdvancedArticleWidget]
    */
    public function wrappedAdvancedArticleWidgetAction()
    {
        return $this->getAdvancedArticleWidgetContent(true);
    }

    public function getAdvancedArticleWidgetContent($wrapped = false, Article $article = null)
    {
        $article = $article ? : AAWEditorService::getArticle();
        // dump($article);exit;
        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/AdvancedArticleWidget/widget.php';
        // dump($viewPath); exit;
        $response = [
            'view' => $this->renderWidget(($wrapped ? 'Wrapped' : '').'AdvancedArticleWidget', $viewPath, [
                'wrapped' => $wrapped,
                'article' => $article,
                'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain(),
                'viewerContent' => $this->getViewerContent($article),
                'editorContent' => $this->getEditorContent($article)
            ]),
            'data' => [
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function getViewerContent(Article $article)
    {

        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/AdvancedArticleWidget/viewer.php';
        return $this->renderWidget('AdvancedArticleWidget_viewer', $viewPath, [
            'article' => $article,
            'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain()
        ]);
    }

    /**
     * AAWEditor
    */

    public function getEditorContent(Article $article)
    {
        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/AdvancedArticleWidget/editor.php';
        return $this->renderWidget('AdvancedArticleWidget_editor', $viewPath, [
            'article' => $article,
            'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain(),
            'toolbarContent' => $this->getArticleToolbarContent($article)
        ]);
    }

    public function getArticleToolbarContent(Article $article)
    {
        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/AdvancedArticleWidget/toolbarOfArticle.php';
        return $this->renderWidget('AdvancedArticleWidget_toolbarOfArticle', $viewPath, [
            'article' => $article,
            'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain()
        ]);
    }

    public function getAjaxResponse(Article $article)
    {
        $response = [
            'view' => [
                'toolbar' => $this->getArticleToolbarContent($article),
                'viewer' => $this->getViewerContent($article)
            ],
            'data' => [
            ]
        ];

        return $this->widgetResponse($response);
    }

    // ArticleParagraph

    /**
    * Route: [name: AAWEditor_addArticleParagraph, paramChain: /AAWEditor/addArticleParagraph]
    */
    public function aawToolbarAddArticleParagraphAction()
    {
        $articleId = App::getContainer()->getRequest()->get('articleId');
        $article = AAWEditorService::getArticle($articleId);
        AAWEditorService::addArticleParagraph($article);
        $article = AAWEditorService::getArticle($articleId);

        return $this->getAjaxResponse($article);
    }

    /**
    * Route: [name: AAWEditor_editArticleParagraph, paramChain: /AAWEditor/editArticleParagraph]
    */
    public function aawToolbarEditArticleParagraphAction()
    {
        
    }

    /**
    * Route: [name: AAWEditor_removeArticleParagraph, paramChain: /AAWEditor/removeArticleParagraph]
    */
    public function aawToolbarRemoveArticleParagraphAction()
    {
        
    }

    // ArticleColumn

    /**
    * Route: [name: AAWEditor_addArticleColumn, paramChain: /AAWEditor/addArticleColumn]
    */
    public function aawToolbarAddArticleColumnAction()
    {
        
    }

    /**
    * Route: [name: AAWEditor_editArticleColumn, paramChain: /AAWEditor/editArticleColumn]
    */
    public function aawToolbarEditArticleColumnAction()
    {
        
    }

    /**
    * Route: [name: AAWEditor_removeArticleColumn, paramChain: /AAWEditor/removeArticleColumn]
    */
    public function aawToolbarRemoveArticleColumnAction()
    {
        
    }

    // ArticleBlock

    /**
    * Route: [name: AAWEditor_addArticleBlock, paramChain: /AAWEditor/addArticleBlock]
    */
    public function aawToolbarAddArticleBlockAction()
    {
        
    }

    /**
    * Route: [name: AAWEditor_editArticleBlock, paramChain: /AAWEditor/editArticleBlock]
    */
    public function aawToolbarEditArticleBlockAction()
    {
        
    }

    /**
    * Route: [name: AAWEditor_removeArticleBlock, paramChain: /AAWEditor/removeArticleBlock]
    */
    public function aawToolbarRemoveArticleBlockAction()
    {
        
    }

    
}
