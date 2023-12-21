<?php
namespace framework\packages\WebshopPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\WebshopPackage\repository\ProductRepository;
use framework\packages\WebshopPackage\repository\ProductCategoryRepository;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\DataGridPackage\service\DataGridBuilder;
use framework\packages\WebshopPackage\entity\Product;

class ProductWidgetController extends WidgetController
{
    /**
    * Route: [name: admin_webshop_products_widget, paramChain: /admin/webshop/products/widget]
    */
    public function adminWebshopProductsWidgetAction()
    {
        // dump(App::get());exit;
        $this->setService('WebshopPackage/entity/Product');
        $this->setService('WebshopPackage/repository/ProductRepository');
        // $this->setService('WebshopPackage/service/WebshopService');
        // $webshopService = $this->getService('WebshopService');
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $dataGridBuilder = new DataGridBuilder('AdminWebshopProductsDataGrid');

        $dataGridBuilder->setValueConversion(['specialPurpose' => [
            null => trans('none'),
            'DeliveryFee' => trans('delivery.fee'),
            'Gift' => trans('gift')
        ]]);
        $dataGridBuilder->addUseUnprocessedAsInputValue('specialPurpose');
        $dataGridBuilder->addPropertyInputType('specialPurpose', 'multiselect');
        $dataGridBuilder->setValueConversion(['isRecommended' => [
            Product::IS_RECOMMENDED_NO => trans('no'),
            Product::IS_RECOMMENDED_YES => trans('yes')
        ]]);
        $dataGridBuilder->addUseUnprocessedAsInputValue('isRecommended');
        $dataGridBuilder->addPropertyInputType('isRecommended', 'multiselect');

        $dataGridBuilder->setValueConversion(['status' => [
            '0' => trans('disabled'),
            '1' => trans('active'),
            '2' => trans('out.of.stock'),
            '3' => trans('discontinued')
        ]]);
        $dataGridBuilder->setPrimaryRepository($this->getService('ProductRepository'));
        $dataGrid = $dataGridBuilder->getDataGrid();

        if ($dataGrid->preload()) {
            $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopProductsWidget/widget.php';
            $response = [
                'view' => $this->renderWidget('AdminWebshopProductsWidget', $viewPath, [
                    'container' => $this->getContainer(),
                    'renderedGrid' => $dataGrid->render(false)['view']
                ]),
                'data' => []
            ];

            return $this->widgetResponse($response);
        } else {
            $response = $dataGrid->render(false);

            return $this->widgetResponse($response);
        }
    }

    /**
    * Route: [name: admin_webshop_product_edit, paramChain: /admin/webshop/product/edit]
    */
    public function adminWebshopProductEditAction()
    {
        // dump($this->getContainer()->getRequest()->getAll());exit;
        $this->wireService('FormPackage/service/FormBuilder');
        // $this->wireService('WebshopPackage/entity/ProductCategory');
        $this->getContainer()->wireService('WebshopPackage/repository/ProductCategoryRepository');
        $productCategoryRepo = new ProductCategoryRepository();
        $productId = (int)$this->getContainer()->getRequest()->get('id');
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('WebshopPackage');
        $formBuilder->setSubject('editProduct');
        $formBuilder->setSchemaPath('WebshopPackage/form/EditProductSchema');
        $formBuilder->setPrimaryKeyValue($this->getContainer()->getRequest()->get('id'));
        $formBuilder->addExternalPost('id');

        $form = $formBuilder->createForm();
        // dump($new);exit;

        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopProductsWidget/editProduct.php';
        $response = [
            'view' => $this->renderWidget('editProduct', $viewPath, [
                'container' => $this->getContainer(),
                'form' => $form,
                'productId' => $productId,
                'productCategories' => $productCategoryRepo->findAllOnWebsite()
            ]),
            'data' => [
                'formIsValid' => $form->isValid(),
                'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll(),
                'label' => !$productId ? trans('create.new.product') : trans('edit.product')
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_product_delete, paramChain: /admin/webshop/product/delete]
    */
    public function adminWebshopProductDeleteAction()
    {
        $this->getContainer()->wireService('WebshopPackage/repository/ProductRepository');
        $repo = new ProductRepository();
        $id = (int)$this->getContainer()->getRequest()->get('id');
        $entity = $repo->find($id);

        /**
         * Checking if user permitted this entity
        */
        if ($entity && $entity->checkCorrectWebsite() == false) {
            $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
            $securityEventHandler->addEvent('TESTING_FOREIGN_DATA', $id, 'InvoiceHeaderId');
        }

        $repo->remove($id);

        // dump($alma); exit;

        $response = [
            'view' => ''
        ];

        return $this->widgetResponse($response);
    }

    
}
