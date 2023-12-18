<?php
namespace framework\packages\WebshopPackage\controller;

use App;
use framework\component\helper\DateUtils;
use framework\component\helper\StringHelper;
use framework\component\parent\WidgetController;
use framework\packages\DataGridPackage\service\DataGridBuilder;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\WebshopPackage\entity\CartTrigger;
use framework\packages\WebshopPackage\entity\Product;
use framework\packages\WebshopPackage\repository\CartTriggerRepository;
use framework\packages\WebshopPackage\repository\ProductRepository;

class CartTriggerWidgetController extends WidgetController
{
    public function __construct()
    {
        $this->getContainer()->setService('WebshopPackage/service/WebshopService');
    }

    /**
    * Route: [name: admin_AdminWebshopCartTriggersWidget, paramChain: /admin/AdminWebshopCartTriggersWidget]
    */
    public function adminWebshopCartTriggersWidgetAction()
    {
        // dump(App::get());exit;
        $this->setService('WebshopPackage/repository/CartTriggerRepository');
        // $this->setService('WebshopPackage/service/WebshopService');
        // $webshopService = $this->getService('WebshopService');
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $dataGridBuilder = new DataGridBuilder('AdminWebshopCartTriggersDataGrid');

        // $dataGridBuilder->setValueConversion(['specialPurpose' => [
        //     null => trans('none'),
        //     'DeliveryFee' => trans('delivery.fee')
        // ]]);
        // $dataGridBuilder->addUseUnprocessedAsInputValue('specialPurpose');
        // $dataGridBuilder->addPropertyInputType('specialPurpose', 'multiselect');
        
        $dataGridBuilder->setValueConversion(['status' => [
            '0' => trans('disabled'),
            '1' => trans('active')
        ]]);

        $dataGridBuilder->setPrimaryRepository($this->getService('CartTriggerRepository'));
        $dataGrid = $dataGridBuilder->getDataGrid();
        $response = $dataGrid->render();

        return $this->widgetResponse($response);
    }

    public function adminWebshopCartTriggerNewAction()
    {
        // $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopCartTriggersWidget/new.php';
        // $response = [
        //     'view' => $this->renderWidget('AdminWebshopResetWidget', $viewPath, [
        //         'container' => $this->getContainer(),
        //         'agreementMessage' => $agreementMessage
        //     ]),
        //     'data' => []
        // ];

        return $this->adminWebshopCartTriggerEditAction(true);
    }

    public function adminWebshopCartTriggerEditAction($new = false)
    {
        App::getContainer()->wireService('WebshopPackage/entity/CartTrigger');
        App::getContainer()->wireService('WebshopPackage/entity/Product');
        App::getContainer()->wireService('WebshopPackage/repository/ProductRepository');
        // dump($this->getContainer()->getRequest()->getAll());exit;
        App::getContainer()->wireService('FormPackage/service/FormBuilder');
        // $this->wireService('WebshopPackage/entity/ProductCategory');
        // $this->wireService('WebshopPackage/repository/CartTriggerRepository');
        // $repo = new CartTriggerRepository();
        $id = (int)$this->getContainer()->getRequest()->get('id');
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('WebshopPackage');
        $formBuilder->setSubject('editCartTrigger');
        $formBuilder->setSchemaPath('WebshopPackage/form/EditCartTriggerSchema');
        $formBuilder->setPrimaryKeyValue($this->getContainer()->getRequest()->get('id'));
        $formBuilder->addExternalPost('id');

        $form = $formBuilder->createForm();

        $productRepository = new ProductRepository();

        $products = $productRepository->findBy([
            'conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()],
                ['key' => 'special_purpose', 'value' => [Product::SPECIAL_PURPOSE_DELIVERY_FEE, Product::SPECIAL_PURPOSE_GIFT]],
            ],
            'orderBy' => [['field' => 'id', 'direction' => 'ASC']]
        ]);

        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopCartTriggersWidget/editModal.php';
        $response = [
            'view' => $this->renderWidget('editCartTrigger', $viewPath, [
                'container' => $this->getContainer(),
                'form' => $form,
                'products' => $products,
                'directionsOfChange' => [
                    CartTrigger::DIRECTION_OF_CHANGE_APPLY => [
                        'title' => trans('direction.of.change.apply'),
                    ],
                    CartTrigger::DIRECTION_OF_CHANGE_DISCARD => [
                        'title' => trans('direction.of.change.discard'),
                    ]
                ],
                'effectCausingStuffs' => [
                    CartTrigger::EFFECT_CAUSING_STUFF_COUNTRY_ALPHA2 => [
                        'title' => 'effect.causing.stuff.country.alpha2'
                    ],
                    CartTrigger::EFFECT_CAUSING_STUFF_ZIP_CODE_MASK => [
                        'title' => 'effect.causing.stuff.zip.code.mask'
                    ],
                    CartTrigger::EFFECT_CAUSING_STUFF_GROSS_TOTAL_PRICE => [
                        'title' => 'effect.causing.stuff.gross.total.price'
                    ],
                    CartTrigger::EFFECT_CAUSING_STUFF_AUTOMATIC => [
                        'title' => 'effect.causing.stuff.automatic'
                    ]
                ],
                'effectOperators' => [
                    CartTrigger::EFFECT_OPERATOR_EQUALS => [
                        'title' => 'equals'
                    ],
                    CartTrigger::EFFECT_OPERATOR_NOT_EQUALS => [
                        'title' => 'not.equals'
                    ],
                    CartTrigger::EFFECT_OPERATOR_LESS_THAN => [
                        'title' => 'less.than'
                    ],
                    CartTrigger::EFFECT_OPERATOR_MORE_THAN => [
                        'title' => 'more.than'
                    ]
                ]
                // 'productCategoryId' => $productCategoryId,
                // 'productCategories' => $this->arrangeProductCategories($productCategoryId, $repo->findAllOnWebsite())
            ]),
            'data' => [
                'formIsValid' => $form->isValid(),
                'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll(),
                'label' => !$id ? trans('create.new.cart.trigger') : trans('edit.cart.trigger')
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function adminWebshopCartTriggerDeleteAction()
    {
        $response = [
            'view' => '',
            'data' => []
        ];

        return $this->widgetResponse($response);
    }
}