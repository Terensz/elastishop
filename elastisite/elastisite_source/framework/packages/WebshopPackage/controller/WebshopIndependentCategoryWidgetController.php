<?php
namespace framework\packages\WebshopPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\WebshopPackage\repository\ProductCategoryRepository;
use framework\packages\WebshopPackage\repository\ProductRepository;

// use framework\component\parent\JsonResponse;
// use framework\kernel\utility\BasicUtils;
// use framework\kernel\utility\FileHandler;
// use framework\component\parent\ImageResponse;
// use framework\packages\ToolPackage\service\Uploader;
// use framework\packages\FormPackage\service\FormBuilder;
// use framework\packages\WebshopPackage\repository\ProductRepository;
// use framework\packages\WebshopPackage\entity\ProductCategory;
// use framework\packages\WebshopPackage\repository\ProductCategoryRepository;
// use framework\packages\WebshopPackage\service\WebshopService;
// use framework\packages\WebshopPackage\repository\ProductPriceActiveRepository;
// use framework\packages\WebshopPackage\entity\ProductPriceActive;
// use framework\packages\WebshopPackage\repository\CartRepository;
// use framework\packages\WebshopPackage\repository\CartItemRepository;
// use framework\packages\WebshopPackage\repository\ShipmentRepository;
// use framework\component\exception\ElastiException;
// use framework\packages\ToolPackage\service\Grid\GridFactory;
// use framework\packages\FrameworkPackage\service\GridAjaxInterface;
// use framework\packages\DataGridPackage\service\DataGridBuilder;
// use framework\packages\ToolPackage\service\Mailer;

class WebshopIndependentCategoryWidgetController extends WidgetController
{
    public function __construct()
    {
        $this->getContainer()->setService('WebshopPackage/service/WebshopService');
    }

    /**
    * Route: [name: widget_independentCategoryWidget, paramChain: /widget/independentCategoryWidget]
    */
    public function webshopIndependentCategoryWidgetAction()
    {
        // dump(App::getContainer()->getUrl()->getParamChain());exit;
        $products = [];
        $categorySlug = App::getContainer()->getUrl()->getSubRoute();

        $this->getContainer()->wireService('WebshopPackage/repository/ProductCategoryRepository');
        $catRepo = new ProductCategoryRepository();
        $category = $catRepo->findOneBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'status', 'value' => 1],
            ['key' => 'slug', 'value' => $categorySlug],
            ['key' => 'is_independent', 'value' => '1']
        ]]);

        if ($category) {
            $this->getContainer()->wireService('WebshopPackage/repository/ProductRepository');
            $prodRepo = new ProductRepository();
            $products = $prodRepo->findBy(['conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()],
                ['key' => 'product_category_id', 'value' => $category->getId()],
                ['key' => 'status', 'value' => 1]
            ]]);

            // dump($products);
        }

        // dump($category);exit;
        
        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopIndependentCategoryWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('WebshopPaymentWidget', $viewPath, [
                'container' => $this->getContainer(),
                'category' => $category,
                'products' => $products
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }
}