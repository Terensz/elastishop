<?php
namespace framework\packages\WebshopPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\WebshopPackage\repository\ProductCategoryRepository;
use framework\packages\WebshopPackage\responseAssembler\WebshopResponseAssembler;
use framework\packages\WebshopPackage\service\WebshopService;
use framework\packages\WebshopPackage\responseAssembler\WebshopResponseAssembler_Categories;
use framework\packages\WebshopPackage\responseAssembler\WebshopResponseAssembler_HistoryProductList;
use framework\packages\WebshopPackage\service\WebshopCartService;

// use framework\packages\UserPackage\repositorx\TemporaryAccountRepository;
// use framework\packages\UserPackage\repositorx\TemporaryPersonRepository;
// use framework\packages\WebshopPackage\repository\ProductImageRepository;

class WebshopWidgetController extends WidgetController
{
    public function __construct()
    {
    }

    /**
    * Route: [name: webshop_refreshHistoryProductList, paramChain: /webshop/refreshHistoryProductList]
    */
    public function webshopRefreshHistoryProductListAction()
    {
        // $webshopService = $this->getContainer()->getService('WebshopService');
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler_HistoryProductList');
        $response = WebshopResponseAssembler_HistoryProductList::assembleResponse();
        // $response = [
        //     'view' => $response['view'],
        //     'data' => []
        // ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_sideOfferWidget, paramChain: /admin/webshop/sideOfferWidget]
    */
    public function webshopSideOfferWidgetAction()
    {
        // $webshopService = $this->getContainer()->getService('WebshopService');


        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopSideOfferWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('WebshopSideOfferWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_myOrdersWidget, paramChain: /webshop/myOrdersWidget]
    */
    public function webshopMyOrdersWidgetAction()
    {
        $this->setService('WebshopPackage/repository/ShipmentRepository');
        $shipmentRepo = $this->getContainer()->getService('ShipmentRepository');
        $webshopService = $this->getContainer()->getService('WebshopService');
        $shipments = $shipmentRepo->findBy(['conditions' => [
            ['key' => 'user_account_id', 'value' => $this->getContainer()->getUser()->getId()]
        ]]);
        
        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopMyOrdersWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('WebshopMyOrdersWidget', $viewPath, [
                'container' => $this->getContainer(),
                'shipments' => $shipments
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_isInactiveWidget, paramChain: /webshop/isInactiveWidget]
    */
    // public function webshopIsInactiveWidgetAction()
    // {
    //     $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopIsInactiveWidget/widget.php';
    //     $response = [
    //         'view' => $this->renderWidget('WebshopIsInactiveWidget', $viewPath, [
    //             // 'container' => $this->getContainer(),
    //             // 'shipments' => $shipments
    //         ]),
    //         'data' => []
    //     ];

    //     return $this->widgetResponse($response);
    // }

    /**
    * Route: [name: webshop_addToCart, paramChain: /webshop/addToCart]
    */
    // public function webshopCartAddAction()
    // {
    //     App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
    //     $productPriceActiveId = (int)$this->getContainer()->getRequest()->get('offerId');
    //     $newQuantity = $this->getContainer()->getRequest()->get('newQuantity');
    //     if ($newQuantity !== null && !is_numeric($newQuantity)) {
    //         $newQuantity = 0;
    //     }
    //     $addedQuantity = $newQuantity === null ? 1 : null;

    //     $cartItem = WebshopCartService::addToCart($productPriceActiveId, $addedQuantity, $newQuantity);
    //     // dump($cartItem);exit;


    //     // $response = [
    //     //     'view' => '',
    //     //     'data' => [
    //     //         'cartOfferIds' => WebshopCartService::getCartActiveProductPriceIds(),
    //     //         'cartItemId' => isset($cartItem) ? $cartItem->getId() : null,
    //     //         'toastTitle' => trans('product.added.to.cart'),
    //     //         'toastBody' => isset($cartItem) ? ($this->getSession()->getLocale() == 'en' 
    //     //             ? $cartItem->getProduct()->getNameEn() 
    //     //             : $cartItem->getProduct()->getName()) : null,
    //     //     ]
    //     // ];

    //     // return $this->widgetResponse($response);

    //     App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
    //     $return = WebshopResponseAssembler::renderSections([WebshopResponseAssembler::SECTION_SIDE_CART]); //exit;

    //     // dump($return);exit;

    //     return $return;
    // }

    /**
    * Route: [name: webshop_removeFromCart, paramChain: /webshop/removeFromCart]
    */
    // public function webshopCartRemoveAction()
    // {
    //     App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');

    //     $productPriceActiveId = (int)$this->getContainer()->getRequest()->get('offerId');
    //     // $webshopService = $this->getContainer()->getService('WebshopService');
    //     $cartItem = WebshopCartService::removeFromCart($productPriceActiveId, 1);
    //     // dump($cartItem);exit;
    //     $response = [
    //         'view' => '',
    //         'data' => [
    //             'cartOfferIds' => WebshopCartService::getCartActiveProductPriceIds(),
    //             'cartItemId' => isset($cartItem) ? $cartItem->getId() : null,
    //             'toastTitle' => trans('product.removed.from.cart'),
    //             'toastBody' => isset($cartItem) ? ($this->getSession()->getLocale() == 'en' 
    //                 ? $cartItem->getProduct()->getNameEn() 
    //                 : $cartItem->getProduct()->getName()) : null,
    //         ]
    //     ];

    //     return $this->widgetResponse($response);
    // }

    /**
     * This route is no more.
    * Route: [name: webshop_sideCart_widget, paramChain: /webshop/sideCartWidget]
    */
    // public function webshopSideCartWidgetAction()
    // {
    //     App::getContainer()->getService('WebshopService');
    //     App::getContainer()->getService('WebshopCartService');

    //     WebshopCartService::removeObsoleteCarts();
    //     WebshopCartService::removeUnboundCartItems();
    //     $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopSideCartWidget/widget.php';
    //     // dump(WebshopService::getCart());
    //     // dump('alma');exit;
    //     $response = [
    //         'view' => $this->renderWidget('WebshopSideCartWidget', $viewPath, [
    //             'container' => $this->getContainer(),
    //             'cart' => WebshopCartService::getCart(),
    //             'hasUnconfirmedOrder' => WebshopService::hasUnconfirmedOrder()
    //         ]),
    //         'data' => []
    //     ];

    //     return $this->widgetResponse($response);
    // }

    // public function renderWebshopProductList_OLD($products, $productCategory, $listParams)
    // {
    //     $webshopService = $this->getContainer()->getService('WebshopService');
    //     $examinedProducts = $this->examineAndSortProductList($products);

    //     if ($listParams['specialTag'] == $webshopService::NON_LISTABLE_PRODUCTS) {
    //         $products = $examinedProducts['nonListableProducts'];
    //     } else {
    //         $products = $examinedProducts['listableProducts'];
    //     }

    //     $cols = 3;
    //     $productRows = $this->fillProductRows($products, $cols);
    //     $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopProductListWidget/productList.php';
    //     return $this->renderWidget('webshopProductList', $viewPath, [
    //         'cartOfferIds' => $webshopService->getCartActiveProductPriceIds(),
    //         'productCategory' => $productCategory,
    //         'productRows' => $productRows,
    //         'container' => $this->getContainer(),
    //         'errors' => $examinedProducts['errors'],
    //         'webshopService' => $webshopService
    //     ]);
    // }

    /**
    * Route: [name: webshop_productList, paramChain: /webshop/productList]
    */
    // public function webshopProductListAction()
    // {
    //     $this->wireService('WebshopPackage/repository/ProductRepository');
    //     $productRepo = new ProductRepository();
    //     $searchTerm = $this->getContainer()->getRequest()->get('webshop_search_term');
    //     if (!$searchTerm || $searchTerm == '') {
    //         $products = $productRepo->findAllAvailable();
    //     } else {
    //         $products = $productRepo->inaccurateSearch($searchTerm);
    //     }
    //     $examinedProducts = $this->examineProducts($products);
    //     $products = $examinedProducts['processedProducts'];
    //     $cols = 3;
    //     $productRows = $this->fillProductRows($products, $cols);

    //     $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopProductListWidget/productList.php';
    //     $response = [
    //         'view' => $this->renderWidget('webshopProductList', $viewPath, [
    //             'productRows' => $productRows,
    //             'container' => $this->getContainer(),
    //             'errors' => $examinedProducts['errors']
    //         ]),
    //         'data' => []
    //     ];

    //     return $this->widgetResponse($response);
    // }

    /**
    * Route: [name: webshop_categoryWidget, paramChain: /webshop/categoryWidget]
    */
    // public function webshopCategoryWidgetAction()
    // {
    //     App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler_Categories');
    //     return WebshopResponseAssembler_Categories::assembleCategories();
    // }


}
