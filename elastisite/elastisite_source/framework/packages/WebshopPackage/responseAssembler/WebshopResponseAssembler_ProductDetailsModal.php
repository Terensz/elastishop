<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\core\WidgetResponse;
use framework\component\parent\Service;
use framework\kernel\view\ViewRenderer;
use framework\packages\WebshopPackage\repository\ProductRepository;
use framework\packages\WebshopPackage\service\WebshopCartService;
use framework\packages\WebshopPackage\service\WebshopProductService;
use framework\packages\WebshopPackage\service\WebshopRequestService;
use framework\packages\WebshopPackage\service\WebshopService;

/**
 * This class assembles both the modal and the standalone page.
*/
class WebshopResponseAssembler_ProductDetailsModal extends Service
{
    public static function assembleResponse($processedRequestData = null)
    {
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler_ProductDetails');

        return WebshopResponseAssembler_ProductDetails::assembleResponse($processedRequestData);
    }

    // public static function assembleProductInfo()
    // {
    //     $productId = (int)$this->getContainer()->getRequest()->get('productId');
    //     $selectedImageId = (int)$this->getContainer()->getRequest()->get('selectedImageId');
    //     $this->getContainer()->setService('WebshopPackage/service/WebshopService');
    //     $this->getContainer()->wireService('WebshopPackage/repository/ProductRepository');
    //     $repo = new ProductRepository();
    
    //     $product = $repo->find($productId);
    
    //     $isIndependent = $product->getProductCategory()->getIsIndependent() == 1 ? true : false;
    //     // dump($isIndependent);exit;
    
    //     $productImages = $product->getProductImage();
    //     // dump($product);exit;
    //     // dump($product->getProductImage());exit;
    //     // $productImages = $repo->findOneBy(['conditions' => [['key' => 'slug', 'value' => $slug]]]);
    
    //     // $priceData = array();
    //     // foreach ($form->getEntity()->getShipmentItem() as $shipmentItem) {
    //     //     $priceData[$shipmentItem->getProduct()->getId()] = WebshopPriceService::getAnalyzedPriceData($shipmentItem->getProductPrice()->getId());
    //     // }
    
    //     $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopProductInfoWidget/widget.php';
    //     $response = [
    //         'view' => $this->renderWidget('WebshopProductInfoWidget', $viewPath, [
    //             // 'container' => $this->getContainer(),
    //             'selectedImageId' => $selectedImageId,
    //             'httpDomain' => App::getContainer()->getUrl()->getHttpDomain(),
    //             'isIndependent' => $isIndependent,
    //             'defaultCurrency' => $isIndependent ? null : App::getContainer()->getConfig()->getProjectData('defaultCurrency'),
    //             'cartOfferIds' => $isIndependent ? null : $this->getService('WebshopService')->getCartActiveProductPriceIds(),
    //             'priceData' => $isIndependent ? null : WebshopPriceService::getActivePriceData($product->getId()),
    //             // 'webshopService' => $this->getContainer()->getService('WebshopService'),
    //             // 'webshopFinanceService' => $this->getContainer()->getService('WebshopPriceService'),
    //             'product' => $product,
    //             'productImages' => $productImages
    //         ]),
    //         'data' => [
    //             'title' => $product->getName()
    //         ]
    //     ];
    // }
}