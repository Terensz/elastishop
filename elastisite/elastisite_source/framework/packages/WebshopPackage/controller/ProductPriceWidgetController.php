<?php
namespace framework\packages\WebshopPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\component\parent\ImageResponse;
use framework\packages\WebshopPackage\repository\CurrencyRepository;
use framework\packages\FinancePackage\service\VATProfileHandler;
use framework\packages\WebshopPackage\entity\Product;
use framework\packages\WebshopPackage\repository\ProductRepository;
use framework\packages\WebshopPackage\entity\ProductCategory;
use framework\packages\WebshopPackage\repository\ProductCategoryRepository;
use framework\packages\WebshopPackage\repository\ProductPriceRepository;
use framework\packages\WebshopPackage\repository\ProductPriceActiveRepository;
use framework\packages\ToolPackage\service\Grid\GridFactory;
use framework\packages\FrameworkPackage\service\GridAjaxInterface;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\PaymentPackage\service\GeneralPaymentService;
use framework\packages\UserPackage\entity\UserAccount;
use framework\packages\UserPackage\entity\Person;
use framework\packages\WebshopPackage\entity\ProductPrice;

class ProductPriceWidgetController extends WidgetController
{
    /**
    * Route: [name: admin_webshop_productPrice_list, paramChain: /admin/webshop/productPrice/list]
    */
    public function adminWebshopProductPriceListAction()
    {
        $productId = (int)$this->getContainer()->getRequest()->get('productId');
        // dump($productId);exit;
        $this->getContainer()->wireService('WebshopPackage/entity/ProductPrice');
        $this->getContainer()->wireService('WebshopPackage/repository/ProductPriceRepository');
        $productPriceRepo = new ProductPriceRepository();

        $this->getContainer()->wireService('WebshopPackage/repository/ProductPriceActiveRepository');
        $productPriceActiveRepo = new ProductPriceActiveRepository();

        $productPrices = $productPriceRepo->findBy(['conditions' => [['key' => 'product_id', 'value' => $productId]]]);
        $listPricesCount = 0;
        $discountPricesCount = 0;
        foreach ($productPrices as $productPrice) {
            if ($productPrice->getPriceType() == ProductPrice::PRICE_TYPE_LIST) {
                $listPricesCount++;
            }
            if ($productPrice->getPriceType() == ProductPrice::PRICE_TYPE_DISCOUNT) {
                $discountPricesCount++;
            }
        }

        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopProductsWidget/productPriceList.php';
        $response = [
            'view' => $this->renderWidget('editProduct', $viewPath, [
                'container' => $this->getContainer(),
                'productId' => $productId,
                'productPriceList' => $productPriceRepo->findBy(['conditions' => [['key' => 'product_id', 'value' => $productId]]]),
                'productPriceActive' => $productPriceActiveRepo->findOneBy(['conditions' => [['key' => 'product_id', 'value' => $productId]]]),
                'listPricesCount' => $listPricesCount,
                'discountPricesCount' => $discountPricesCount
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_productPrice_new, paramChain: /admin/webshop/productPrice/new]
    */
    public function adminWebshopProductPriceNewAction()
    {
        $this->getContainer()->wireService('WebshopPackage/repository/ProductPriceRepository');
        $productPriceRepo = new ProductPriceRepository();

        $this->getContainer()->wireService('WebshopPackage/repository/ProductRepository');
        $productRepo = new ProductRepository();

        $productId = (int)$this->getContainer()->getRequest()->get('productId');
        $this->setService('FormPackage/service/FormBuilder');
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('WebshopPackage');
        $formBuilder->setSubject('newProductPrice');
        $formBuilder->setSchemaPath('WebshopPackage/form/NewProductPriceSchema');
        $formBuilder->setSaveRequested(false);
        // $formBuilder->setPrimaryKeyValue($this->getContainer()->getRequest()->get('id'));
        $formBuilder->addExternalPost('productId');

        $form = $formBuilder->createForm();
        if ($form->isSubmitted() && $form->isValid()) {
            App::getContainer()->wireService('WebshopPackage/repository/CurrencyRepository');
            App::getContainer()->wireService('PaymentPackage/service/GeneralPaymentService');

            $currencyRepository = new CurrencyRepository();
            $currency = $currencyRepository->findOneBy(['conditions' => [['key' => 'code', 'value' => GeneralPaymentService::getActiveCurrency()]]]);
            $productPrice = $form->getEntity();
            $product = $productRepo->find($productId);
            $productPrice->setProduct($product);
            $productPrice->setCurrency($currency);
            // dump($productPrice);exit;
            $productPrice = $productPriceRepo->store($productPrice);
            // dump($productPrice);exit;
        }

        $productPrices = $productPriceRepo->findBy(['conditions' => [['key' => 'product_id', 'value' => $productId]]]);
        $listPricesCount = 0;
        foreach ($productPrices as $productPrice) {
            if ($productPrice->getPriceType() == 'list') {
                $listPricesCount++;
            }
        }
        // dump($productPrices);exit;
        $vatProfile = App::getContainer()->getConfig()->getProjectData('VATProfile');
        $this->getContainer()->wireService('FinancePackage/service/VATProfileHandler');
        $taxOfficeConfig = VATProfileHandler::getConfig($vatProfile);
        $vatPercentages = $taxOfficeConfig['taxOffice.allowedVATPercentages'];
        // dump($taxOfficeConfig);exit;


        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopProductsWidget/newProductPrice.php';
        $response = [
            'view' => $this->renderWidget('newProductPrice', $viewPath, [
                'container' => $this->getContainer(),
                'form' => $form,
                'productId' => $productId,
                'listPricesCount' => $listPricesCount,
                'vatProfile' => $vatProfile,
                'vatPercentages' => $vatPercentages
                // 'productCategories' => $productCategoryRepo->findAll()
            ]),
            'data' => [
                'formIsValid' => $form->isValid(),
                'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll()
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_productPrice_activate, paramChain: /admin/webshop/productPrice/activate]
    */
    public function adminWebshopProductPriceActivateAction()
    {
        $this->getContainer()->wireService('WebshopPackage/repository/ProductRepository');
        $productRepo = new ProductRepository();

        $this->getContainer()->wireService('WebshopPackage/repository/ProductPriceRepository');
        $productPriceRepo = new ProductPriceRepository();

        $this->getContainer()->wireService('WebshopPackage/repository/ProductPriceActiveRepository');
        $productPriceActiveRepo = new ProductPriceActiveRepository();

        $productId = (int)$this->getContainer()->getRequest()->get('productId');
        $productPriceId = (int)$this->getContainer()->getRequest()->get('productPriceId');
        $oldProductPriceActive = $productPriceActiveRepo->findOneBy(['conditions' => [['key' => 'product_id', 'value' => $productId]]]);
        if ($oldProductPriceActive) {
            $productPriceActiveRepo->remove($oldProductPriceActive->getId());
        }
        $productPriceActive = $productPriceActiveRepo->createNewEntity();
        $productPriceActive->setProduct($productRepo->find($productId));
        $productPriceActive->setProductPrice($productPriceRepo->find($productPriceId));
        $productPriceActiveRepo->store($productPriceActive);
        // dump($productPriceActive); exit;

        $response = [
            'view' => ''
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_productPrice_delete, paramChain: /admin/webshop/productPrice/delete]
    */
    public function adminWebshopProductPriceDeleteAction()
    {
        // dump($this->getContainer()->getRequest()->getAll());//exit;
        $this->getContainer()->wireService('WebshopPackage/repository/ProductPriceRepository');
        $repo = new ProductPriceRepository();
        $id = (int)$this->getContainer()->getRequest()->get('id');
        $repo->remove($id);
        // dump($repo); exit;

        $response = [
            'view' => ''
        ];

        return $this->widgetResponse($response);
    }

    
}
