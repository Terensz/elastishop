<?php
namespace framework\packages\SeoPackage\controller;

use framework\component\parent\WidgetController;

class KeywordsWidgetController extends WidgetController
{
    // public function getPageKeywordRepository()
    // {
    //     $this->setService('SeoPackage/repository/PageKeywordRepository');
    //     return $this->getService('PageKeywordRepository');
    // }

    // public function getPageKeywords()
    // {
    //     $repo = $this->getPageKeywordRepository();
    //     $pageKeywords = $repo->findAll();
    //     return $pageKeywords ? : [];
    // }

    // /**
    // * Route: [name: admin_keywords_widget, paramChain: /admin/keywords/widget]
    // */
    // public function adminKeywordsWidgetAction()
    // {
    //     $viewPath = 'framework/packages/SeoPackage/view/widget/AdminKeywordsWidget/widget.php';

    //     $response = [
    //         'view' => $this->renderWidget('AdminKeywordsWidget', $viewPath, [
    //             'container' => $this->getContainer(),
    //             'pageKeywords' => $this->getPageKeywords()
    //         ]),
    //         'data' => []
    //     ];

    //     // dump($response);exit;
    //     return $this->widgetResponse($response);
    // }

    // /**
    // * Route: [name: admin_keywords_list, paramChain: /admin/keywords/list]
    // */
    // public function adminKeywordsListAction()
    // {
    //     $viewPath = 'framework/packages/SeoPackage/view/widget/AdminKeywordsWidget/existingKeywords.php';

    //     $response = [
    //         'view' => $this->renderWidget('AdminKeywordsWidget', $viewPath, [
    //             'container' => $this->getContainer(),
    //             'pageKeywords' => $this->getPageKeywords()
    //         ]),
    //         'data' => []
    //     ];

    //     // dump($response);exit;
    //     return $this->widgetResponse($response);
    // }

    // /**
    // * Route: [name: admin_keywords_add, paramChain: /admin/keywords/add]
    // */
    // public function adminKeywordsAddAction()
    // {
    //     $repo = $this->getPageKeywordRepository();
    //     $keyword = $this->getRequest()->get('keyword');
    //     $keywordExists = $repo->findOneBy(['conditions' => [['key' => 'name', 'value' => $keyword]]]);
    //     if (!$keywordExists) {
    //         $pageKeyword = $repo->createNewEntity();
    //         $pageKeyword->setName($keyword);
    //         $repo->store($pageKeyword);
    //     }
        
    //     $response = [
    //         'view' => '',
    //         'data' => []
    //     ];

    //     // dump($response);exit;
    //     return $this->widgetResponse($response);
    // }

    // /**
    // * Route: [name: admin_keywords_delete, paramChain: /admin/keywords/delete]
    // */
    // public function adminKeywordsDeleteAction()
    // {
    //     $repo = $this->getPageKeywordRepository();
    //     $repo->remove((int)$this->getRequest()->get('id'));
    //     $response = [
    //         'view' => '',
    //         'data' => []
    //     ];

    //     // dump($response);exit;
    //     return $this->widgetResponse($response);
    // }
}
