<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\parent\Service;
use framework\kernel\base\ConfigReader;
use framework\kernel\view\ViewRenderer;
use framework\packages\WebshopPackage\repository\ProductPriceActiveRepository;
use framework\packages\WebshopPackage\service\WebshopCartService;
use framework\packages\WebshopPackage\service\WebshopProductService;

class WebshopResponseAssembler_SetCartItemQuantityModal extends Service
{
    public static function assembleResponse($processedRequestData = null, $data = [])
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        $cartDataSet = WebshopCartService::assembleCartDataSet();

        // dump($cartData); exit;
        $offerId = (int)App::getContainer()->getRequest()->get('offerId');
        $submitted = App::getContainer()->getRequest()->get('submitted');
        $submitted = ConfigReader::mendValue($submitted);
        // $submitted = ConfigReader::mendValue();

        $oldQuantity = null;
        $newQuantity = null;
        // $actualCartItemData = null;
        $productData = null;
        if (!empty($cartDataSet['cart']['cartItems'])) {
            foreach ($cartDataSet['cart']['cartItems'] as $cartDataSetRow) {
                // dump($cartDataSetRow);
                $cartItemData = $cartDataSetRow['cartItem'];
                // dump($cartItemData);
                if (isset($cartItemData['product']) && isset($cartItemData['product']['activeProductPrice']['offerId']) && $cartItemData['product']['activeProductPrice']['offerId'] == $offerId) {
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

        if ($productPriceActive) {
            if (!$productData) {
                App::getContainer()->wireService('WebshopPackage/service/WebshopProductService');
                // dump($productPriceActive);exit;
                $rawProductsData = $productPriceActive->getProduct()->getRepository()->getProductsData(App::getContainer()->getSession()->getLocale(), [
                    'productId' => $productPriceActive->getProduct()->getId(),
                ], []);
                $productsData = WebshopProductService::arrangeProductsData($rawProductsData);
                $productData = isset($productsData[0]) ? $productsData[0] : null;
            }

            if ($submitted) {
                $newQuantity = (int)App::getContainer()->getRequest()->get('newQuantity');
                WebshopCartService::addToCart($offerId, $newQuantity);
                // if (!$actualCartItemData) {
                //     if ($newQuantity) {
                //         WebshopCartService::addToCart($offerId, $newQuantity);
                //     }
                // } else {
                //     WebshopCartService::addToCart($offerId, $newQuantity);
                // }
                // dump($newQuantity);
                // dump($actualCartItemData); exit;
            }
        }

        $offeredQuantity = $newQuantity ? : ($oldQuantity ? : 1);

        $viewParams = [
            'validOffer' => $productPriceActive ? true : false,
            'offerId' => $offerId,
            'offeredQuantity' => $offeredQuantity,
            'productData' => $productData,
            'cartDataSet' => $cartDataSet ? : [],
        ];

        // $data = [
        //     'offerId' => $offerId
        // ];

        $viewPath = 'framework/packages/WebshopPackage/view/Sections/SideCart/SetCartItemQuantityModal.php';
        $view = ViewRenderer::renderWidget('WebshopPackage_SetCartItemQuantityModal', $viewPath, $viewParams);

        return [
            'view' => $view,
            'data' => [
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