<?php
namespace framework\packages\NewsletterPackage\controller;

use framework\component\parent\WidgetController;
use framework\packages\DataGridPackage\service\DataGridBuilder;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\NewsletterPackage\entity\NewsletterCampaign;
use framework\kernel\EntityManager\EntityChecker;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\packages\ToolPackage\repository\FileRepository;
use framework\packages\ToolPackage\entity\Grid;
use framework\packages\ToolPackage\service\Grid\GridFactory;
use framework\packages\NewsletterPackage\entity\Newsletter;

class NewsletterDispatchingWidgetController extends WidgetController
{
    public function getNewsletterService()
    {
        $this->setService('NewsletterPackage/service/NewsletterService');
        return $this->getService('NewsletterService');
    }

    public function getDispatchRepository()
    {
        $this->setService('NewsletterPackage/repository/NewsletterDispatchRepository');
        return $this->getService('NewsletterDispatchRepository');
    }

    public function getDispatchProcessRepository()
    {
        $this->setService('NewsletterPackage/repository/NewsletterDispatchProcessRepository');
        return $this->getService('NewsletterDispatchProcessRepository');
    }

    /**
    * Route: [name: admin_newsletter_dispatchProcesses_widget}, paramChain: /admin/newsletter/dispatchProcesses/widget]
    */
    public function adminNewsletterDispatchProcessesWidgetAction()
    {
        $viewPath = 'framework/packages/NewsletterPackage/view/widget/AdminNewsletterDispatchProcessesWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminNewsletterDispatchProcessesWidget', $viewPath, [
                'container' => $this->getContainer(),
            ]),
            'data' => []
        ];
        
        return $this->widgetResponse($response);
    }

    /**
    * name: admin_newsletter_dispatchProcesses_list, paramChain: /admin/newsletter/dispatchProcesses/list
    */
    public function adminNewsletterDispatchProcessesListAction($widgetResponse = true)
    {
        $this->wireService('DataGridPackage/service/DataGridBuilder');

        $repo = $this->getDispatchProcessRepository();
        $dataGridBuilder = new DataGridBuilder('AdminNewsletterDispatchProcessesGrid');
        $dataGridBuilder->setValueConversion(['mode' => $this->getNewsletterService()->getNewsletterDispatchProcessModes()]);
        $dataGridBuilder->setValueConversion(['status' => $this->getNewsletterService()->getNewsletterDispatchProcessStatuses()]);
        $dataGridBuilder->setCreateNewText(trans('create.new.newsletter.dispatch.process'));
        // $dataGridBuilder->setDataGridId('AdminOpenGraphsGrid');
        $dataGridBuilder->setPrimaryRepository($repo);
        $dataGrid = $dataGridBuilder->getDataGrid();
        $dataGrid->setListActionUrl($this->getUrl()->getHttpDomain().'/admin/newsletter/dispatchProcesses/list');
        $dataGrid->setNewActionUrl($this->getUrl()->getHttpDomain().'/admin/newsletter/dispatchProcess/new');
        $dataGrid->setEditActionUrl($this->getUrl()->getHttpDomain().'/admin/newsletter/dispatchProcess/edit');
        $dataGrid->setDeleteActionUrl($this->getUrl()->getHttpDomain().'/admin/newsletter/dispatchProcess/delete');
        // $dataGrid->setJavaScriptOnDeleteConfirmed('OpenGraphImageHandler.loadGallery();');
        $response = $dataGrid->render();

        return $widgetResponse ? $this->widgetResponse($response) : $response;
    }

    /**
    * name: admin_newsletter_dispatchProcess_new, paramChain: /admin/newsletter/dispatchProcess/new
    */
    public function adminNewsletterDispatchProcessNewAction($widgetResponse = true)
    {
        return $this->adminNewsletterDispatchProcessEditAction(true);
    }

    /**
    * Route: [name: admin_newsletter_dispatchProcess_edit, paramChain: /admin/newsletter/dispatchProcess/edit]
    */
    public function adminNewsletterDispatchProcessEditAction($new = false)
    {
        $id = $new ? null : $this->getContainer()->getRequest()->get('id');
        
        // $submitted = $this->getContainer()->getRequest()->getAll() == ['id' => null] ? false : true;
        $submitted = array_keys($this->getContainer()->getRequest()->getAll()) == ['id'] ? false : true;
        // $submitted = is_array($this->getContainer()->getRequest()->getAll()) ? true : false;

        $form = $this->getAdminNewsletterDispatchProcessEditForm($id, $submitted);

        $this->wireService('NewsletterPackage/entity/NewsletterCampaign');
        $this->wireService('NewsletterPackage/entity/NewsletterDispatchProcess');
        $this->setService('NewsletterPackage/repository/NewsletterCampaignRepository');
        $newsletterCampaignRepo = $this->getService('NewsletterCampaignRepository');
        $newsletterCampaigns = $newsletterCampaignRepo->findBy(['conditions' => [
            ['key' => 'status', 'value' => NewsletterCampaign::STATUS_ACTIVE]
        ]]);

        $viewPath = 'framework/packages/NewsletterPackage/view/widget/AdminNewsletterDispatchProcessesWidget/edit.php';
        $response = [
            'view' => $this->renderWidget('AdminNewsletterCampaignsWidget_edit', $viewPath, [
                'id' => $id,
                // 'status' => $form->getEntity()->getStatus(),
                'isEditable' => $form->getEntity()->getRepository()->isEditable($form->getEntity()->getId()),
                'modes' => $id ? $this->getNewsletterService()->getNewsletterDispatchProcessMode($form->getEntity()->getMode()) : $this->getNewsletterService()->getNewsletterDispatchProcessModes(),
                'newsletterCampaigns' => $newsletterCampaigns,
                'submitted' => $submitted,
                'container' => $this->getContainer(),
                'posts' => $this->getRequest()->getAll(),
                'statuses' => $id ? $this->getNewsletterService()->getNewsletterDispatchProcessEditStatuses() : $this->getNewsletterService()->getNewsletterDispatchProcessNewStatuses(),
                'form' => $form 
            ]),
            'data' => [
                'label' => $id ? trans('edit.newsletter.dispatch.process') : trans('create.new.newsletter.dispatch.process'),
                'formIsValid' => $form->isValid()
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function getAdminNewsletterDispatchProcessEditForm($id = null, $submitted = false)
    {
        $this->wireService('FormPackage/service/FormBuilder');
        // dump($this->getContainer()->getRequest()->getAll());exit;
        $formBuilder = new FormBuilder();
        $formBuilder->setSchemaPath('NewsletterPackage/form/EditNewsletterDispatchProcessSchema');
        $formBuilder->setPackageName('NewsletterPackage');
        $formBuilder->setSubject('EditNewsletterDispatchProcess');
        $formBuilder->setPrimaryKeyValue($id);
        $formBuilder->addExternalPost('id');
        $formBuilder->setAutoSubmit(false);
        $formBuilder->setSubmitted($submitted);
        $form = $formBuilder->createForm();
        return $form;
    }

    /**
    * name: admin_newsletter_dispatchProcess_delete, paramChain: /admin/newsletter/dispatchProcess/delete
    */
    public function adminNewsletterDispatchProcessDeleteAction()
    {
        $repo = $this->getDispatchProcessRepository();
        $repo->removeBy(['id' => $this->getContainer()->getRequest()->get('id')]);

        $response = [
            'view' => ''
            ,
            'data' => [
                'id' => $this->getContainer()->getRequest()->get('id')
            ]
        ];

        return $this->widgetResponse($response);
    }





    /**
    * Route: [name: admin_newsletter_processSending_widget}, paramChain: /admin/newsletter/processSending/widget]
    */
    public function adminNewsletterProcessSendingWidgetAction()
    {
        $viewPath = 'framework/packages/NewsletterPackage/view/widget/AdminNewsletterProcessSendingWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminNewsletterProcessSendingWidget', $viewPath, [
                'container' => $this->getContainer(),
            ]),
            'data' => []
        ];
        
        return $this->widgetResponse($response);
    }
}