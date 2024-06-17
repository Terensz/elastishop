<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\core\WidgetResponse;
use framework\component\parent\Service;
use framework\kernel\view\ViewRenderer;
use framework\packages\WebshopPackage\service\WebshopRequestService;
use framework\packages\WebshopPackage\service\WebshopService;

class WebshopResponseAssembler_FilterBar extends Service
{
    public static function assembleResponse($processedRequestData = null)
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');

        $processedRequestData = $processedRequestData ? : WebshopRequestService::getProcessedRequestData();
        $locale = App::getContainer()->getSession()->getLocale();

        // dump($processedRequestData);exit;

        /**
         * Putting together the search links.
         * One for search all, one for search in actual category.
        */
        $searchLinkBase = '/'.WebshopRequestService::getSlugTransRef(WebshopService::TAG_WEBSHOP, $locale);
        $searchSlug = WebshopRequestService::getSlugTransRef(WebshopService::TAG_SEARCH, $locale);
        $searchLinkBaseAll = $searchLinkBase.'/'.$searchSlug.'/';
        $searchLinkBaseCategory = null;
        if ($processedRequestData['categorySlug']) {
            $searchLinkBaseCategory = $searchLinkBase.'/'.WebshopRequestService::getSlugTransRef(WebshopService::TAG_CATEGORY, $locale).'/'.WebshopRequestService::getSlugTransRef($processedRequestData['categorySlug'], $locale).'/'.$searchSlug.'/';
        }
        
        $isMixedSearch = $processedRequestData['categorySlug'] && $processedRequestData['searchTerm'];

        $viewParams = [
            'isMixedSearch' => $isMixedSearch,
            'searchTerm' => $processedRequestData['searchTerm'] ? : '',
            'categorySlug' => $processedRequestData['categorySlug'],
            'searchLinkData' => [
                'searchLinkBase' => $searchLinkBase,
                'searchLinkBaseAll' => $searchLinkBaseAll,
                'searchLinkBaseCategory' => $searchLinkBaseCategory
            ]
        ];

        // dump($viewParams); exit;

        $viewPath = 'framework/packages/WebshopPackage/view/Sections/ProductList/FilterBar.php';
        $view = ViewRenderer::renderWidget('WebshopPackage_FilterBar', $viewPath, $viewParams);

        return [
            'view' => $view,
            'data' => [
            ]
        ];

        // $response = [
        //     'view' => $view,
        //     'data' => [
        //         // 'closeModal' => $form->isValid() ? true : false
        //     ]
        // ];

        // return WidgetResponse::create($response);
    }
}