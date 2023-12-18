<?php
namespace framework\packages\DocumentationPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;

class DocumentationWidgetController extends WidgetController
{
    public $documentsCache = [];

    const FILES_PATH_BASE = 'framework/packages/DocumentationPackage/view/widget/DocumentationContentWidget/files/';

    public function getCategorizedDocuments()
    {
        if (!empty($this->documentsCache)) {
            return $this->documentsCache;
        }

        $documents = [];
        $locale = App::getContainer()->getSession()->get('locale');
        $categories = FileHandler::getAllDirNames(self::FILES_PATH_BASE.$locale, 'source');
        foreach ($categories as $category) {
            $docFiles = FileHandler::getAllFileNames(self::FILES_PATH_BASE.$locale.'/'.$category, 'remove', 'source');
            foreach ($docFiles as $docFile) {
                $documents[$category][] = $docFile;
            }
        }
        $this->documentsCache = $documents;

        return $documents;
    }

    /**
    * Route: [name: widget_DocumentationSubmenuWidget, paramChain: /widget/DocumentationSubmenuWidget]
    */
    public function documentationSubmenuWidgetAction()
    {
        $viewPath = 'framework/packages/DocumentationPackage/view/widget/DocumentationSubmenuWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('DocumentationSubmenuWidget', $viewPath, [
                'categorizedDocuments' => $this->getCategorizedDocuments(),
                'httpDomain' => App::getContainer()->getUrl()->getHttpDomain(),
                'actualCategory' => App::getContainer()->getUrl()->getSubRoute(),
                'actualSlug' => App::getContainer()->getUrl()->getDetails()[0]
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponseWithWordExplanation($response);
    }

    /**
    * Route: [name: widget_DocumentationContentWidget, paramChain: /widget/DocumentationContentWidget]
    */
    public function documentationContentWidgetAction()
    {
        $locale = App::getContainer()->getSession()->get('locale');
        $actualCategory = App::getContainer()->getUrl()->getSubRoute();
        $categories = FileHandler::getAllDirNames(self::FILES_PATH_BASE.$locale, 'source');
        // dump(self::FILES_PATH_BASE.$locale);
        // $category = null;
        $rawCategory = null;
        foreach ($categories as $categoryLoop) {
            $categoryLoopParts = explode('_', $categoryLoop);
            $category = count($categoryLoopParts) == 2 ? $categoryLoopParts[1] : $categoryLoopParts[0];
            if ($category == $actualCategory) {
                $rawCategory = $categoryLoop;
            }
        }
        // dump($categories);
        // dump($rawCategory); exit;

        $actualSlug = App::getContainer()->getUrl()->getDetails()[0];
        $docFiles = FileHandler::getAllFileNames(self::FILES_PATH_BASE.$locale.'/'.$rawCategory, 'remove', 'source');
        $pathToFile = null;
        foreach ($docFiles as $docFile) {
            $slugParts = explode('_', $docFile);
            $slug = count($slugParts) == 2 ? $slugParts[1] : $slugParts[0];
            if ($slug == $actualSlug) {
                $pathToFile = self::FILES_PATH_BASE.$locale.'/'.$rawCategory.'/'.$docFile.'.php';
            }
            // $documents[$actualCategory][] = $slug;
        }

        $docView = $pathToFile ? $this->renderView($pathToFile) : '';
        $viewPath = 'framework/packages/DocumentationPackage/view/widget/DocumentationContentWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('DocumentationSubmenuWidget', $viewPath, [
                'docView' => $docView,
                'documentTitle' => str_replace('-', '.', $actualSlug)
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponseWithWordExplanation($response);
    }
}
