<?php
namespace framework\packages\WebshopPackage\controller;

use App;
use framework\component\helper\DateUtils;
use framework\component\helper\StringHelper;
use framework\component\parent\WidgetController;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\WebshopPackage\repository\ShipmentRepository;
use framework\packages\DataGridPackage\service\DataGridBuilder;
use framework\packages\PaymentPackage\repository\PaymentRepository;
use framework\packages\PaymentPackage\service\GeneralPaymentService;
use framework\packages\StatisticsPackage\service\ApexCharts\ChartDataService;
use framework\packages\StatisticsPackage\service\ApexCharts\ChartViewService;
use framework\packages\UserPackage\entity\Address;
use framework\packages\ToolPackage\service\Mailer;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\service\WebshopService;
use framework\packages\WebshopPackage\service\ShipmentService;

class WebshopAdminWidgetController extends WidgetController
{
    public function __construct()
    {
        $this->getContainer()->setService('WebshopPackage/service/WebshopService');
    }

    /**
    * Route: [name: admin_webshop_resetWidget, paramChain: /admin/webshop/resetWidget]
    */
    public function adminWebshopResetWidgetAction()
    {
        $name = $this->getContainer()->getDbConnection()->getName();
        // dump($name);exit;
        $agreementMessage = null;
        $submitted = $this->getContainer()->getRequest()->get('submitted');
        if ($submitted) {
            $agreementAccepted = $this->getContainer()->getRequest()->get('WebshopPackage_resetWebshop_agreement');
            if ($agreementAccepted) {
                $this->resetWebshop();
            } else {
                $agreementMessage = 'missing.agreement';
            }
            // dump($agreementAccepted);exit;
        }

        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopResetWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminWebshopResetWidget', $viewPath, [
                'container' => $this->getContainer(),
                'agreementMessage' => $agreementMessage
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    public function resetWebshop()
    {
        $this->getContainer()->setService('WebshopPackage/repository/ProductCategoryRepository');
        $this->getContainer()->setService('WebshopPackage/repository/ProductRepository');
        $this->getContainer()->setService('WebshopPackage/repository/ProductPriceActiveRepository');
        $this->getContainer()->setService('WebshopPackage/repository/ProductPriceRepository');
        $this->getContainer()->setService('WebshopPackage/repository/CartRepository');
        $this->getContainer()->setService('WebshopPackage/repository/CartItemRepository');
        $this->getContainer()->setService('WebshopPackage/repository/ShipmentRepository');
        $this->getContainer()->setService('WebshopPackage/repository/ShipmentItemRepository');
        $this->getContainer()->setService('WebshopPackage/repository/ProductImageRepository');

        $cartItemRepo = $this->getContainer()->getService('CartItemRepository');
        $cartItemRepo->removeAll(true);
        $cartRepo = $this->getContainer()->getService('CartRepository');
        $cartRepo->removeAll(true);
        $shipmentItemRepo = $this->getContainer()->getService('ShipmentItemRepository');
        $shipmentItemRepo->removeAll(true);
        $shipmentRepo = $this->getContainer()->getService('ShipmentRepository');
        $shipmentRepo->removeAll(true);
        $productPriceActiveRepo = $this->getContainer()->getService('ProductPriceActiveRepository');
        $productPriceActiveRepo->removeAll(true);
        $productPriceRepo = $this->getContainer()->getService('ProductPriceRepository');
        $productPriceRepo->removeAll(true);
        $productImageRepo = $this->getContainer()->getService('ProductImageRepository');
        $productImageRepo->removeAll(true);
        $productCategoryRepo = $this->getContainer()->getService('ProductCategoryRepository');
        $productCategoryRepo->removeAll(true);
        $productRepo = $this->getContainer()->getService('ProductRepository');
        $productRepo->removeAll(true);
    }

    /**
    * Route: [name: admin_webshop_config_widget, paramChain: /admin/webshop/config/widget]
    */
    public function adminWebshopConfigWidgetAction()
    {
        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopConfigWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminWebshopConfigWidget', $viewPath, [
                'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain()
                // 'container' => $this->getContainer()
                // 'webshopSettings' => $webshopSettings
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_config_list, paramChain: /admin/webshop/config/list]
    */
    public function adminWebshopConfigListAction()
    {
        // $webshopService = $this->getService('WebshopService');
        $webshopSettings = $this->getWebshopSettingsArray();
        // dump($webshopSettings);exit;

        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopConfigWidget/list.php';
        $response = [
            'view' => $this->renderWidget('AdminWebshopConfigWidget_list', $viewPath, [
                // 'container' => $this->getContainer()
                'settings' => $webshopSettings
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_config_edit, paramChain: /admin/webshop/config/edit]
    */
    public function adminWebshopConfigEditAction()
    {
        $this->wireService('WebshopPackage/service/WebshopService');
        $webshopSettings = $this->getWebshopSettingsArray();

        $this->setService('FrameworkPackage/service/SettingsService');
        $settingsService = $this->getService('SettingsService');

        // var_dump($this->getRequest()->getAll());exit;

        $submitted = $this->getContainer()->getRequest()->get('submitted');
        if ($submitted == 'true') {
            $settingsService->processPosts(['WebshopPackage_editConfig_submit']);
        }
        // const OPTION_TRUE = [
        //     'displayedValue' => 'true',
        //     'translateDisplayedValue' => true,
        //     'optionValue' => 'true'
        // ];
        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopConfigWidget/modal.php';
        $response = [
            'view' => $this->renderWidget('AdminWebshopConfigWidget', $viewPath, [
                // 'container' => $this->getContainer()
                'settings' => $webshopSettings,
                'homepageListTypes' => [
                    WebshopService::TAG_DISCOUNTED_PRODUCTS => [
                        'rawValue' => trans(StringHelper::alterToTranslationFormat(WebshopService::TAG_DISCOUNTED_PRODUCTS)),
                        'translateDisplayedValue' => false,
                        'optionKey'=> WebshopService::TAG_DISCOUNTED_PRODUCTS
                    ],
                    // $webshopService::MOST_POPULAR_PRODUCTS,
                    WebshopService::TAG_ALL_PRODUCTS => [
                        // 'rawValue' => WebshopService::TAG_ALL_PRODUCTS,
                        'rawValue' => trans(StringHelper::alterToTranslationFormat(WebshopService::TAG_ALL_PRODUCTS)),
                        'translateDisplayedValue' => false,
                        'optionKey'=> WebshopService::TAG_ALL_PRODUCTS
                    ],
                ]
            ]),
            'data' => [
                'label' => trans('edit.webshop.settings')
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function getWebshopSettingsArray()
    {
        $webshopService = $this->getContainer()->getService('WebshopService');
        $webshopSettings = [];
        foreach ($webshopService::$settings as $settingKey => $settingValue) {
            $webshopSettings[$settingKey] = $webshopService->getDisplayedSetting($settingKey);
        }

        return $webshopSettings;
    }

    /**
    * Route: [name: admin_webshop_storages_widget, paramChain: /admin/webshop/storages/widget]
    */
    public function adminWebshopStoragesWidgetAction()
    {
        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopStoragesWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminWebshopStoragesWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_inwardProcessing_widget, paramChain: /admin/webshop/inward_processing/widget]
    */
    public function adminWebshopInwardProcessingWidgetAction()
    {
        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopInwardProcessingWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminWebshopInwardProcessingWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_stock_widget, paramChain: /admin/webshop/stock/widget]
    */
    public function adminWebshopStockWidgetAction()
    {
        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopStockWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminWebshopStockWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_discounts_widget, paramChain: /admin/webshop/discounts/widget]
    */
    public function adminWebshopDiscountsWidgetAction()
    {
        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopDiscountsWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminWebshopDiscountsWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_shipmentsWidget, paramChain: /admin/webshop/shipments/widget]
    */
    public function adminWebshopShipmentsWidgetAction()
    {
        // return $this->widgetResponse($response);
        App::getContainer()->wireService('WebshopPackage/entity/Shipment');
        App::getContainer()->setService('WebshopPackage/repository/ShipmentRepository');
        App::getContainer()->wireService('WebshopPackage/service/ShipmentService');
        // $webshopService = $this->getService('WebshopService');
        App::getContainer()->wireService('DataGridPackage/service/DataGridBuilder');
        $dataGridBuilder = new DataGridBuilder('AdminWebshopShipmentsDataGrid');
        $dataGridBuilder->setValueConversion(['status' => ShipmentService::getShipmentStatusConversionArray()]);
        $dataGridBuilder->setPrimaryRepository($this->getService('ShipmentRepository'));
        //$dataGridBuilder->setEditActionRoute('admin_webshop_shipment_edit');
        $dataGrid = $dataGridBuilder->getDataGrid();
        $dataGrid->setColorCellByValue([
            'property' => 'status',
            'useValueConversion' => true, // false, if you want to color by processed value
            'values' => [
                Shipment::SHIPMENT_STATUS_DELIVERED => [
                    'background' => '1b1b1b',
                    'font' => 'efefef'
                ],
                Shipment::SHIPMENT_STATUS_ORDER_CANCELLED => [
                    'background' => '575757',
                    'font' => 'efefef'
                ],
                Shipment::SHIPMENT_STATUS_ORDER_PREPARED => [
                    'background' => '0e6a7b',
                    'font' => 'efefef'
                ],
                Shipment::SHIPMENT_STATUS_PREPARED_FOR_DELIVERY => [
                    'background' => '212d64',
                    'font' => 'efefef'
                ]
            ]
        ]);

        $response = $dataGrid->render();

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_runningOrdersWidget, paramChain: /admin/webshop/runningOrders/widget]
    */
    public function adminWebshopRunningOrdersWidgetAction()
    {
        // $webshopService = $this->getService('WebshopService');
        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopRunningOrdersWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminWebshopRunningOrdersWidget', $viewPath, [
                // 'container' => $this->getContainer(),
                'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain(),
                'defaultCurrency' => App::getContainer()->getConfig()->getProjectData('defaultCurrency'),
                'displayedRunningOrders' => WebshopService::getSetting('WebshopPackage_displayedRunningOrders')
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_getOrderedShipmentIds, paramChain: /admin/webshop/getOrderedShipmentIds]
    */
    public function adminWebshopGetOrderedShipmentIdsAction()
    {
        $this->wireService('WebshopPackage/repository/ShipmentRepository');
        // $shipmentRepo = $this->getService('ShipmentRepository');
        // $webshopService = $this->getService('WebshopService');

        $orderedShipmentIds = [];
        $rawOrderedShipmentIds = ShipmentRepository::getOrderedShipmentIds('ASC', WebshopService::getSetting('WebshopPackage_displayedRunningOrders'));
        foreach ($rawOrderedShipmentIds as $orderedShipmentId) {
            $orderedShipmentIds[] = $orderedShipmentId['id'];
        }

        $response = [
            'view' => '',
            'data' => [
                'orderedShipmentIds' => implode(',', $orderedShipmentIds)
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_runningOrders_getListView, paramChain: /admin/webshop/runningOrders/getListView]
    */
    public function adminWebshopRunningOrdersGetListViewAction()
    {
        App::getContainer()->wireService('WebshopPackage/entity/Shipment');
        App::getContainer()->wireService('WebshopPackage/repository/ShipmentRepository');
        App::getContainer()->wireService('WebshopPackage/service/ShipmentService');
        // App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        $collection = ShipmentRepository::getShipmentCollectionWithSpecificStatuses(Shipment::STATUS_COLLECTION_PAID_UNFINISHED_STATUSES);
        $shipmentDataSet = ShipmentService::assembleShipmentDataSet($collection);

        // dump($shipmentDataSet);exit;


        // App::getContainer()->wireService('PaymentPackage/service/GeneralPaymentService');
        // $this->wireService('WebshopPackage/entity/Shipment');
        // $this->setService('WebshopPackage/repository/ShipmentRepository');
        // $shipmentRepo = $this->getService('ShipmentRepository');

        // $shipments = [];
        // $rawOrderedShipmentIds = $shipmentRepo->getOrderedShipmentIds('ASC', WebshopService::getSetting('WebshopPackage_displayedRunningOrders'));
        // $stackedPriceData = array();
        // foreach ($rawOrderedShipmentIds as $orderedShipmentId) {
        //     $shipment = $shipmentRepo->find($orderedShipmentId['id']);
        //     $shipments[] = $shipment;
        //     foreach ($shipment->getShipmentItem() as $shipmentItem) {
        //         $stackedPriceData[$shipment->getId()][$shipmentItem->getProduct()->getId()] = WebshopPriceService::getAnalyzedPriceData($shipmentItem->getProductPrice()->getId());
        //     }
        // }
        
        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopRunningOrdersWidget/listView.php';
        $response = [
            'view' => $this->renderWidget('AdminWebshopRunningOrdersWidget-listView', $viewPath, [
                'shipmentDataSet' => $shipmentDataSet,
                // 'shipments' => $shipments,
                // 'stackedPriceData' => $stackedPriceData,
                // 'currency' => GeneralPaymentService::getActiveCurrency(),
                // 'statuses' => Shipment::$statuses
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_getLastShipmentId, paramChain: /admin/webshop/getLastShipmentId]
    */
    public function adminWebshopGetLastShipmentIdAction()
    {
        $this->setService('WebshopPackage/repository/ShipmentRepository');
        $shipmentRepo = $this->getService('ShipmentRepository');
        $lastShipmentId = $shipmentRepo->getLastShipmentId();
        // dump($lastShipmentId);exit;

        $response = [
            'view' => '',
            'data' => [
                'lastShipmentId' => $lastShipmentId
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_statisticsWidget, paramChain: /admin/webshop/statisticsWidget]
    */
    public function adminWebshopStatisticsWidgetAction()
    {
        $this->getContainer()->wireService('PaymentPackage/repository/PaymentRepository');
        // $chartService = new ChartService();
        
        $paymentRepo = new PaymentRepository();
        $earliestDate = $paymentRepo->getEarliestDate();
        $earliestDateTime = new \DateTime($earliestDate ?? '');

        $periodMonthProperties = DateUtils::getPeriodMonthProperties($earliestDateTime, new \DateTime());
        $minMonthIndex = $periodMonthProperties[0]['monthIndex'];
        $maxMonthIndex = $periodMonthProperties[count($periodMonthProperties) - 1]['monthIndex'];

        // dump($this->getRenderedChartView(0, 'chart1'));
        // dump($periodMonthProperties);exit;
        // dump($periodMonthProperties);
        // dump($earliestDateTime->format('Y-m'));
        // dump($earliestDateTime->format('Y-m-d H:i:s'));
        // exit;

        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopStatisticsWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminVisitsAndPageLoadsWidget', $viewPath, [
                // 'container' => $this->getContainer(),
                'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain(),
                'periodMonthProperties' => $periodMonthProperties,
                'minMonthIndex' => $minMonthIndex,
                'maxMonthIndex' => $maxMonthIndex,
                'chart1' => $this->getRenderedChartView(0, 'chart1'),
                'chart2' => $this->getRenderedChartView(-1, 'chart2')
            ]),
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_statistics_earlierMonth, paramChain: /admin/webshop/statistics/earlierMonth]
    */
    public function adminWebshopStatisticsEarlierMonthAction()
    {
        $monthIndex = $this->getContainer()->getRequest()->get('monthIndex');

        $view = $monthIndex == 'null' ? '' : $this->getRenderedChartView($monthIndex, 'chart3');
        $response = [
            'view' => $view,
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    public function getRenderedChartView($periodStartIndex, $chartId)
    {
        $this->getContainer()->wireService('PaymentPackage/repository/PaymentRepository');
        $this->getContainer()->wireService('StatisticsPackage/service/ApexCharts/ChartDataService');
        $this->getContainer()->wireService('StatisticsPackage/service/ApexCharts/ChartViewService');
        $paymentRepo = new PaymentRepository();
        $stats = $paymentRepo->getStatsByDay($periodStartIndex);

        $chartView = new ChartViewService();
        $chartView->setChartId($chartId);
        $chartView->setChartTitle(trans('total.payment.occasions.and.gross.income').' - '.$stats['currentMonthName']);
        $chartView->setValueAxisTitle(trans('gross.income'));
        $chartView->setCategoryAxisTitle(trans('days.of.period'));
        $chartView->setChartType('line');
        $chartView->setMultiple(true);
        $chartView->setChartData(ChartDataService::createData($stats['result'], ['total_payment_occasions', 'gross_income'], 'create_day', [], $stats['periodStartDate'], $stats['periodEndDate']));
        // dump($chart1View->getChartData());dump($stats);exit;

        return $chartView->render();
    }

    /**
    * Route: [name: admin_webshop_shipment_edit, paramChain: /admin/webshop/shipment/edit]
    */
    public function adminWebshopShipmentEditAction()
    {
        // dump($this->getContainer()->getRequest()->getAll());exit;
        $this->wireService('FormPackage/service/FormBuilder');
        $this->wireService('WebshopPackage/entity/Shipment');
        // $webshopService = $this->getContainer()->getService('WebshopService');
        $this->getContainer()->setService('BasicPackage/repository/CountryRepository');
        $countryRepo = $this->getContainer()->getService('CountryRepository');

        App::getContainer()->wireService('WebshopPackage/entity/Shipment');
        App::getContainer()->wireService('WebshopPackage/service/ShipmentService');
        App::getContainer()->wireService('WebshopPackage/repository/ShipmentRepository');
        // $repo = new ShipmentRepository();

        $shipmentId = (int)$this->getContainer()->getRequest()->get('id');


        // App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        $shipmentDataSet = null;
        if ($shipmentId) {
            $collection = ShipmentRepository::getShipmentCollectionFromId($shipmentId);
            $shipmentDataSet = ShipmentService::assembleShipmentDataSet($collection);
        }






        // $closeShipment = $this->getContainer()->getRequest()->get('closeShipment');

        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('WebshopPackage');
        $formBuilder->setSubject('editShipment');
        $formBuilder->setSchemaPath('WebshopPackage/form/EditShipmentSchema');
        $formBuilder->setPrimaryKeyValue($shipmentId);
        $formBuilder->addExternalPost('id');
        $formBuilder->setSaveRequested(false);

        $form = $formBuilder->createForm();

        /**
         * Checking if user permitted this entity
        */
        if ($form->getEntity() && $form->getEntity()->checkCorrectWebsite() == false) {
            $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
            $securityEventHandler->addEvent('TESTING_FOREIGN_DATA', $shipmentId, 'ShipmentId');
        }

        App::getContainer()->wireService('PaymentPackage/service/GeneralPaymentService');
        $currency = GeneralPaymentService::getActiveCurrency();
        // if ($closeShipment) {
        //     $closeShipment = $closeShipment == 'true' ? true : $closeShipment;
        //     $form->getEntity()->setClosed(1);
        // }

        // dump($form);exit;

        if ($form->isValid() === true) {
            $shipment = $form->getEntity();
            // dump($shipment);
            // $shipment->setCountry($shipment->getTemporaryAccount()->getTemporaryPerson()->getAddress()->getCountry());
            // $shipment->setZipCode($shipment->getTemporaryAccount()->getTemporaryPerson()->getAddress()->getZipCode());
            // $shipment->setCity($shipment->getTemporaryAccount()->getTemporaryPerson()->getAddress()->getCity());
            if ($shipment->getUserAccount() != null && $shipment->getUserAccount()->getId() == null) {
                $shipment->setUserAccount(null);
            }

            if ($shipment->getTemporaryAccount()->getTemporaryPerson()) {
                $address = $shipment->getTemporaryAccount()->getTemporaryPerson()->getAddress();
                // dump($address);exit;
                $address->setPerson(null);
                $address = $address->getRepository()->store($address);
                $shipment->getTemporaryAccount()->getTemporaryPerson()->setAddress($address);

                if (!$shipment->getTemporaryAccount()->getTemporaryPerson()->getOrganization() || !$shipment->getTemporaryAccount()->getTemporaryPerson()->getOrganization()->getId()) {
                    $shipment->getTemporaryAccount()->getTemporaryPerson()->setOrganization(null);
                }
            }
            // dump($shipment);exit;
            $shipment = $shipment->getRepository()->store($shipment);
            $form->setEntity($shipment);
            // dump($shipment);exit;
        }

        // $priceData = array();
        // foreach ($form->getEntity()->getShipmentItem() as $shipmentItem) {
        //     $priceData[$shipmentItem->getProduct()->getId()] = WebshopPriceService::getAnalyzedPriceData($shipmentItem->getProductPrice()->getId());
        // }

        // $shipmentItemsViewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopShipmentsWidget/shipmentItems.php';
        // $shipmentItemsView = $this->renderWidget('shipmentItemsView', $shipmentItemsViewPath, [
        //     'shipmentItems' => $form->getEntity()->getShipmentItem(),
        //     'priceData' => $priceData,
        //     'currency' => $currency
        // ]);

        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopShipmentsWidget/editShipment.php';
        $response = [
            'view' => $this->renderWidget('editShipment', $viewPath, [
                'shipmentDataSet' => $shipmentDataSet,
                'removeTemporaryPersonOnCloseShipment' => false, // WebshopService::getSetting('WebshopPackage_removeTemporaryPersonOnCloseShipment')
                'closedShipmentIsEditable' => WebshopService::getSetting('WebshopPackage_closedShipmentIsEditable'),
                'reopenShipmentIsAllowed' => WebshopService::getSetting('WebshopPackage_reopenShipmentIsAllowed'),
                'container' => $this->getContainer(),
                // 'shipmentItemsView' => $shipmentItemsView,
                'statuses' => Shipment::$statuses,
                'countries' => $countryRepo->findAllAvailable(),
                'form' => $form,
                'shipmentId' => $shipmentId,
                'closedStatus' => Shipment::SHIPMENT_STATUS_CLOSED,
                'cancelledStatus' => Shipment::SHIPMENT_STATUS_ORDER_CANCELLED
                // 'orderClosed' => $form->getEntity()->getClosed()
            ]),
            'data' => [
                'formIsValid' => $form->isValid(),
                'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll(),
                'label' => trans('edit.order')
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_shipment_delete, paramChain: /admin/webshop/shipment/delete]
    */
    public function adminWebshopShipmentDeleteAction()
    {
        // dump($this->getContainer()->getRequest()->getAll());//exit;
        $this->getContainer()->wireService('WebshopPackage/repository/ShipmentRepository');
        $repo = new ShipmentRepository();
        $repo->remove($this->getContainer()->getRequest()->get('id'));
        // dump($alma); exit;

        $response = [
            'view' => ''
        ];

        return $this->widgetResponse($response);
    }
}
