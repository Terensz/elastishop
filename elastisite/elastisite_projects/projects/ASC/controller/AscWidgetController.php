<?php
namespace projects\ASC\controller;

use framework\component\parent\WidgetController;
use framework\packages\DataGridPackage\service\DataGridBuilder;
use framework\packages\FormPackage\service\FormBuilder;
use projects\ASC\repository\AscSubscriptionOfferRepository;

class AscWidgetController extends WidgetController 
{
    /**
    * Route: [name: admin_ascSubscriptionOffers_widget, paramChain: /admin/ascSubscriptionOffers/widget]
    */
    public function adminAscSubscriptionOffersWidgetAction()
    {
        // $viewPath = 'projects/ASC/view/widget/AdminAscSubscriptionOffersWidget/widget.php';

        // $response = [
        //     'view' => $this->renderWidget('AdminAscSubscriptionOffersWidget', $viewPath, [
        //         'container' => $this->getContainer(),
        //         'documentTitle' => '',
        //         'message' => ''
        //     ]),
        //     'data' => []
        // ];

        $this->wireService('projects/ASC/repository/AscSubscriptionOfferRepository');
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
        $dataGridBuilder->setPrimaryRepository(new AscSubscriptionOfferRepository());
        $dataGrid = $dataGridBuilder->getDataGrid();
        $dataGrid->setNewActionUrl($this->getUrl()->getHttpDomain().'/admin/ascSubscriptionOffer/new');
        $response = $dataGrid->render();

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_ascSubscriptionOffer_new, paramChain: /admin/ascSubscriptionOffer/new]
    */
    public function adminAscSubscriptionOfferNewAction()
    {
        return $this->adminAscSubscriptionOfferEditAction(true);
    }

    /**
    * Route: [name: admin_ascSubscriptionOffer_edit, paramChain: /admin/ascSubscriptionOffer/edit]
    */
    public function adminAscSubscriptionOfferEditAction($new = false)
    {
        $viewPath = 'projects/ASC/view/widget/AdminAscSubscriptionOffersWidget/edit.php';
        $response = [
            'view' => $this->renderWidget('admin_ascSubscriptionOffer_new', $viewPath, [
                'new' => $new,
                'message' => ''
            ]),
            'data' => [
                'label' => 'alma'
            ]
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    public function getAdminAscSubscriptionOfferForm($id = null)
    {
        $this->wireService('FormPackage/service/FormBuilder');
        // dump($this->getContainer()->getRequest()->getAll());exit;
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('ASC');
        $formBuilder->setSubject('ascSubscriptionOfferEdit');
        $formBuilder->setPrimaryKeyValue($id);
        $formBuilder->addExternalPost('id');
        // $formBuilder->addExternalPost('FrameworkPackage_pageEdit_file');
        $formBuilder->setSaveRequested(false);
        $formBuilder->setAutoSubmit(false);
        $formBuilder->setSubmitted($this->getContainer()->getRequest()->get('submitted') ? : false);
        $form = $formBuilder->createForm();
        return $form;
    }
}