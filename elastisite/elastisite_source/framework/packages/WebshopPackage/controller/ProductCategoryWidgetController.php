<?php
namespace framework\packages\WebshopPackage\controller;

use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\component\parent\ImageResponse;
use framework\packages\WebshopPackage\entity\ProductCategory;
use framework\packages\WebshopPackage\repository\ProductCategoryRepository;
// use framework\packages\ToolPackage\service\Grid\GridFactory;
// use framework\packages\ToolPackage\service\GridAjaxInterface;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\UserPackage\entity\UserAccount;
use framework\packages\UserPackage\entity\Person;
use framework\packages\DataGridPackage\service\DataGridBuilder;
use framework\packages\WebshopPackage\entity\Shipment;

class ProductCategoryWidgetController extends WidgetController
{
    /**
    * Route: [name: admin_webshop_productCategories_widget, paramChain: /admin/webshop/productCategories/widget]
    */
    public function adminWebshopProductCategoriesWidgetAction()
    {
        $this->setService('WebshopPackage/repository/ProductCategoryRepository');
        $this->setService('WebshopPackage/service/WebshopService');
        // $webshopService = $this->getService('WebshopService');
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $dataGridBuilder = new DataGridBuilder('AdminWebshopProductCategoriesDataGrid');
        // $dataGridBuilder->setValueConversion(['status' => $webshopService->getShipmentStatusConversionArray()]);
        $dataGridBuilder->setValueConversion(['status' => [
            '0' => trans('disabled'),
            '1' => trans('active')
        ]]);
        // $dataGridBuilder->addUseUnprocessedAsInputValue('isIndependent');
        // $dataGridBuilder->addPropertyInputType('isIndependent', 'multiselect');
        // $dataGridBuilder->setValueConversion(['isIndependent' => [
        //     '0' => trans('false'),
        //     '1' => trans('true')
        // ]]);
        $dataGridBuilder->setPrimaryRepository($this->getService('ProductCategoryRepository'));
        $dataGrid = $dataGridBuilder->getDataGrid();
        $response = $dataGrid->render();

        return $this->widgetResponse($response);

        // $grid = $this->getListProductCategoriesGrid();
        // $this->getContainer()->wireService('FrameworkPackage/service/GridAjaxInterface');
        // $gridAjaxInterface = new GridAjaxInterface();
        // $gridAjaxInterface->setGridName('editProductCategory');
        // $gridAjaxInterface->setSearchActionParamChain('admin/webshop/productCategory/search');
        // $gridAjaxInterface->setSearchFormName('WebshopPackage_productCategorySearch_form');
        // $gridAjaxInterface->setEditActionParamChain('admin/webshop/productCategory/edit');
        // $gridAjaxInterface->setDeleteActionParamChain('admin/webshop/productCategory/delete');
        // $gridAjaxInterface->setDeleteResponseScript("ProductCategorySearch.search(1);");

        // $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopProductCategoriesWidget/widget.php';
        // $response = [
        //     'view' => $this->renderWidget('AdminWebshopProductCategoriesWidget', $viewPath, [
        //         'container' => $this->getContainer(),
        //         'renderedGrid' => $dataGrid->render(false)
        //     ]),
        //     'data' => []
        // ];
    }

    // public function getListProductCategoriesGrid($filter = null, $page = 1)
    // {
    //     $this->getContainer()->wireService('ToolPackage/service/Grid/GridFactory');
    //     $gridFactory = new GridFactory();
    //     $gridFactory->setUsePager(false);
    //     $gridFactory->setGridName('editProductCategory');
    //     $gridFactory->setRepositoryServiceLink('WebshopPackage/repository/ProductCategoryRepository');
    //     $gridFactory->setAllowCreateNew(true);
    //     $gridFactory->setProperties([
    //         ['name' => 'id', 'title' => 'id'],
    //         ['name' => 'name', 'title' => trans('name'), 'colWidth' => '5'],
    //         // ['name' => 'code', 'title' => trans('code'), 'colWidth' => '2'],
    //         ['name' => 'productCategory.name', 'title' => trans('parent.product.category'), 'colWidth' => '5'],
    //         ['name' => 'status', 'title' => null, 'colWidth' => null]
    //     ]);
    //     $gridFactory->addDeleteLink();
    //     $grid = $gridFactory->create($filter, $page);
    //     return $grid;
    // }

    /**
    * Route: [name: admin_webshop_productCategory_search, paramChain: /admin/webshop/productCategory/search]
    */
    // public function adminWebshopProductCategorySearchAction()
    // {
    //     $searchName = $this->getRequest()->get('WebshopPackage_productCategorySearch_name');
    //     $searchProductCategory = $this->getRequest()->get('WebshopPackage_productCategorySearch_productCategory');
    //     $searchStatus = $this->getRequest()->get('WebshopPackage_productCategorySearch_status');
    //     $page = $this->getRequest()->get('page') ? $this->getRequest()->get('page') : 1;

    //     $filter = array(
    //         'name'                  => $searchName == '' ? null : '%'.$searchName.'%',
    //         'product_category_id'   => $searchProductCategory == '*all*' ? null : $searchProductCategory,
    //         'status'   => $searchStatus == '*all*' ? null : $searchStatus
    //     );
    //     $grid = $this->getListProductCategoriesGrid($filter, $page);
    //     $response = [
    //         'view' => $grid->render(),
    //         'data' => [
    //             'filteredResult' => $grid->getData()
    //         ]
    //     ];
    //     return $this->widgetResponse($response);
    // }

    public function adminWebshopProductCategoryNewAction()
    {
        return $this->adminWebshopProductCategoryEditAction();
    }

    /**
    * Route: [name: admin_webshop_productCategory_edit, paramChain: /admin/webshop/productCategory/edit]
    */
    public function adminWebshopProductCategoryEditAction()
    {
        // dump($this->getContainer()->getRequest()->getAll());exit;
        $this->wireService('FormPackage/service/FormBuilder');
        // $this->wireService('WebshopPackage/entity/ProductCategory');
        $this->wireService('WebshopPackage/repository/ProductCategoryRepository');
        $repo = new ProductCategoryRepository();
        $productCategoryId = (int)$this->getContainer()->getRequest()->get('id');
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('WebshopPackage');
        $formBuilder->setSubject('editProductCategory');
        $formBuilder->setSchemaPath('WebshopPackage/form/EditProductCategorySchema');
        $formBuilder->setPrimaryKeyValue($this->getContainer()->getRequest()->get('id'));
        $formBuilder->addExternalPost('id');

        $form = $formBuilder->createForm();

        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopProductCategoriesWidget/editProductCategory.php';
        $response = [
            'view' => $this->renderWidget('editProductCategory', $viewPath, [
                'container' => $this->getContainer(),
                'form' => $form,
                'productCategoryId' => $productCategoryId,
                'productCategories' => $this->arrangeProductCategories($productCategoryId, $repo->findAllOnWebsite())
            ]),
            'data' => [
                'formIsValid' => $form->isValid(),
                'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll(),
                'label' => !$productCategoryId ? trans('create.new.product.category') : trans('edit.product.category')
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_productCategory_delete, paramChain: /admin/webshop/productCategory/delete]
    */
    public function adminWebshopProductCategoryDeleteAction()
    {
        // dump($this->getContainer()->getRequest()->getAll());//exit;
        $this->getContainer()->wireService('WebshopPackage/repository/ProductCategoryRepository');
        $repo = new ProductCategoryRepository();
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

        $response = [
            'view' => ''
        ];

        return $this->widgetResponse($response);
    }

    private function arrangeProductCategories($productCategoryId, $productCategories)
    {
        $this->getContainer()->wireService('WebshopPackage/entity/Shipment');

        if (!$productCategoryId) {
            return is_array($productCategories) ? $productCategories : array();
        }

        $return = array();
        foreach ($productCategories as $productCategory) {
            // dump($productCategory);
            if ($this->checkProductCategoryDependency($productCategoryId, $productCategory)) {
                if (Shipment::MAXIMUM_PRODUCT_CATEGORY_DEPTH == 0) {
                    $return[] = $productCategory;
                } elseif (Shipment::MAXIMUM_PRODUCT_CATEGORY_DEPTH == 1) {
                    if (!$productCategory->getProductCategory()) {
                        $return[] = $productCategory;
                    }
                } else {
                    /**
                     * @todo !!! 
                     */
                }
            }
        }
        return $return;
    }

    private function checkProductCategoryDependency($productCategoryId, $productCategory)
    {
        if ($productCategory->getId() == $productCategoryId) {
            return false;
        }
        $this->wireService('WebshopPackage/entity/ProductCategory');
        if ($productCategory->getProductCategory() instanceof ProductCategory) {
            return $this->checkProductCategoryDependency($productCategoryId, $productCategory->getProductCategory());
        } else {
            return true;
        }
    }
}
