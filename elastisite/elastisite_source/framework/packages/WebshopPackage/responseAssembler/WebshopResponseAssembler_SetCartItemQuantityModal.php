<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\parent\Service;
use framework\kernel\base\ConfigReader;
use framework\kernel\view\ViewRenderer;
use framework\packages\WebshopPackage\dataProvider\PackDataProvider;
use framework\packages\WebshopPackage\repository\ProductPriceActiveRepository;
use framework\packages\WebshopPackage\service\WebshopCartService;
use framework\packages\WebshopPackage\dataProvider\ProductListDataProvider;

class WebshopResponseAssembler_SetCartItemQuantityModal extends Service
{
    public static function assembleResponse($processedRequestData = null, $data = [])
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        App::getContainer()->wireService('WebshopPackage/dataProvider/PackDataProvider');
        $packDataSet = PackDataProvider::assembleDataSet(WebshopCartService::getCart());

        // dump($cartData); exit;
        $offerId = (int)App::getContainer()->getRequest()->get('offerId');
        $submitted = App::getContainer()->getRequest()->get('submitted');
        $submitted = ConfigReader::mendValue($submitted);
        // $submitted = ConfigReader::mendValue();

        $oldQuantity = null;
        $newQuantity = null;
        // $actualCartItemData = null;
        $productData = null;
        if (!empty($packDataSet['pack']['packItems'])) {
            foreach ($packDataSet['pack']['packItems'] as $packDataSetRow) {
                // dump($packDataSetRow);
                $cartItemData = $packDataSetRow;
                // dump($cartItemData);
                if (isset($cartItemData['product']) && isset($cartItemData['product']['activePrice']['offerId']) && $cartItemData['product']['activePrice']['offerId'] == $offerId) {
                    // dump($cartItemData['product']);exit;
                    if ($cartItemData['product']['specialPurpose']) {
                        echo 'Did not win';exit;
                    }
                    // specialPurpose
                    $oldQuantity = $cartItemData['quantity'];
                    // $cartItemData['product']['name'];
                    $productData = $cartItemData['product'];
                    // $actualCartItemData = $cartItemData;
                }
            }
        }

        App::getContainer()->wireService('WebshopPackage/repository/ProductPriceActiveRepository');
        $productPriceActiveRepository = new ProductPriceActiveRepository();
        $productPriceActive = $productPriceActiveRepository->find($offerId);
        // dump($productPriceActive);exit;

        $addedToCart = null;
        if ($productPriceActive) {
            if (!$productData) {
                App::getContainer()->wireService('WebshopPackage/dataProvider/ProductListDataProvider');
                // dump($productPriceActive);exit;
                $rawProductsData = $productPriceActive->getProduct()->getRepository()->getProductsData(App::getContainer()->getSession()->getLocale(), [
                    'productId' => $productPriceActive->getProduct()->getId(),
                ], []);
                $productsData = ProductListDataProvider::arrangeProductsData($rawProductsData['productData']);
                $productData = isset($productsData[0]) ? $productsData[0] : null;
            }

            if ($submitted) {
                $newQuantity = (int)App::getContainer()->getRequest()->get('newQuantity');
                $addedToCart = WebshopCartService::addToCart($offerId, $newQuantity);

                // dump($addedToCart);exit;
                // if (!$actualCartItemData) {
                //     if ($newQuantity) {
                //         WebshopCartService::addToCart($offerId, $newQuantity);
                //     }
                // } else {
                //     WebshopCartService::addToCart($offerId, $newQuantity);
                // }
                // dump($addedToCart);exit;
                // dump($actualCartItemData); exit;
            }
        }

        if (!$addedToCart && $submitted) {
            dump('!$addedToCart');exit;
            // dump($submitted);exit;
        }

        $offeredQuantity = $newQuantity ? : ($oldQuantity ? : 1);

        $viewParams = [
            'validOffer' => $productPriceActive ? true : false,
            'offerId' => $offerId,
            'offeredQuantity' => $offeredQuantity,
            'productData' => $productData,
            'packDataSet' => $packDataSet ? : [],
        ];

        // $data = [
        //     'offerId' => $offerId
        // ];
        $closeModalAfterSubmit = true;
        $viewPath = 'framework/packages/WebshopPackage/view/Sections/SideCart/SetCartItemQuantityModal.php';
        $view = ViewRenderer::renderWidget('WebshopPackage_SetCartItemQuantityModal', $viewPath, $viewParams);

        return [
            'view' => $view,
            'data' => [
                'addedToCart' => $addedToCart ? $addedToCart->getId() : null,
                'toastTitle' => trans('system.message'),
                'toastBody' => trans('cart.updated'),
                'offerId' => $offerId
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

    // public function get
}