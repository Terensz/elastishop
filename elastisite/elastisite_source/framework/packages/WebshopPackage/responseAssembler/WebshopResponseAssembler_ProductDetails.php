<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\core\WidgetResponse;
use framework\component\parent\Service;
use framework\kernel\view\ViewRenderer;
use framework\packages\WebshopPackage\dataProvider\PackDataProvider;
use framework\packages\WebshopPackage\repository\ProductRepository;
use framework\packages\WebshopPackage\service\WebshopCartService;
use framework\packages\WebshopPackage\dataProvider\ProductListDataProvider;
use framework\packages\WebshopPackage\service\WebshopRequestService;
use framework\packages\WebshopPackage\service\WebshopService;

/**
 * This class assembles both the modal and the standalone page.
*/
class WebshopResponseAssembler_ProductDetails extends Service
{
    public static function assembleResponse($processedRequestData = null)
    {
        App::getContainer()->wireService('WebshopPackage/dataProvider/ProductListDataProvider');
        App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        App::getContainer()->wireService('WebshopPackage/repository/ProductRepository');
        $productRepository = new ProductRepository();

        $productId = (int)App::getContainer()->getRequest()->get('productId');

        if (!$productId) {
            $pageRoute = App::getContainer()->getRouting()->getPageRoute();
            if ($pageRoute->getName() == 'webshop_showProduct') {

                // $paramChainParts = explode('/', );
                $urlDetails = App::getContainer()->getUrl()->getDetails();
                if (isset($urlDetails[0])) {
                    $productId = (int)$urlDetails[0];
                }
                // dump($urlDetails);
            }
            // dump($pageRoute);
        }

        if (!$productId) {
            return null;
        }

        App::getContainer()->wireService('WebshopPackage/dataProvider/PackDataProvider');
        $packDataSet = PackDataProvider::assembleDataSet(WebshopCartService::getCart());
        $oldQuantity = null;
        // $actualCartItemData = null;
        $productData = null;
        if (!empty($packDataSet['pack']['packItems'])) {
            // dump($packDataSet['cart']['cartItems']);
            foreach ($packDataSet['pack']['packItems'] as $packDataSetRow) {
                
                $cartItemData = $packDataSetRow;
                // dump($cartItemData);

                if (isset($cartItemData['product']) && isset($cartItemData['product']['id']) && $cartItemData['product']['id'] == $productId) {
                    $oldQuantity = $cartItemData['quantity'];
                    // $cartItemData['product']['name'];
                    $productData = $cartItemData['product'];
                    // $actualCartItemData = $cartItemData;
                }
            }
        }

        // dump($oldQuantity);exit;
        // dump($packDataSet);
        // exit;

        // dump($productPriceActive);exit;
        $rawProductsData = $productRepository->getProductsData(App::getContainer()->getSession()->getLocale(), [
            'productId' => $productId,
        ], [
            'getDescription' => true
        ]);
        $productsData = ProductListDataProvider::arrangeProductsData($rawProductsData);
        $productData = isset($productsData[0]) ? $productsData[0] : null;
        $locale = App::getContainer()->getSession()->getLocale();

        $offeredQuantity = $oldQuantity ? : 1;

        // dump($productData);exit;

        $viewParams = [
            'packDataSet' => $packDataSet,
            'productData' => $productData,
            'offeredQuantity' => $offeredQuantity,
            // 'productDescription' => $productData['productDescription'],
            'localizedProductInfoLinkBase' => WebshopRequestService::getSlugTransRef(WebshopService::TAG_WEBSHOP, $locale).'/'.WebshopRequestService::getSlugTransRef(WebshopService::TAG_SHOW_PRODUCT, $locale).'/'
        ];

        $viewPath = 'framework/packages/WebshopPackage/view/Sections/ProductDetails/ProductDetailsModal.php';
        $view = ViewRenderer::renderWidget('WebshopPackage_ProductDetailsModal', $viewPath, $viewParams);

        // dump($productData);exit;
        // $productData['productName']
        $productName = $productData['name'];

        return [
            'view' => $view,
            'data' => [
                'modalLabel' => trans('product').': '.$productName
            ]
        ];
    }
}