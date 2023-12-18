<?php
namespace framework\packages\NewsletterPackage\controller;

use framework\component\parent\WidgetController;
use framework\packages\DataGridPackage\service\DataGridBuilder;
use framework\packages\FormPackage\service\FormBuilder;
use framework\kernel\EntityManager\EntityChecker;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\packages\ToolPackage\repository\FileRepository;
use framework\packages\ToolPackage\entity\Grid;
use framework\packages\ToolPackage\service\Grid\GridFactory;
use framework\packages\NewsletterPackage\entity\Newsletter;

class NewsletterCampaignWidgetController extends WidgetController
{
    public function getNewsletterService()
    {
        $this->setService('NewsletterPackage/service/NewsletterService');
        return $this->getService('NewsletterService');
    }

    public function getRepository()
    {
        $this->setService('NewsletterPackage/repository/NewsletterCampaignRepository');
        return $this->getService('NewsletterCampaignRepository');
    }

    /**
    * Route: [name: admin_newsletter_campaigns_widget}, paramChain: /admin/newsletter/campaigns/widget]
    */
    public function adminNewsletterCampaignsWidgetAction()
    {
        $viewPath = 'framework/packages/NewsletterPackage/view/widget/AdminNewsletterCampaignsWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminNewsletterCampaignsWidget', $viewPath, [
                'container' => $this->getContainer(),
            ]),
            'data' => []
        ];
        
        return $this->widgetResponse($response);
    }

    /**
    * name: admin_newsletter_campaigns_list, paramChain: /admin/newsletter/campaigns/list
    */
    public function adminNewsletterCampaignsListAction($widgetResponse = true)
    {
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $repo = $this->getRepository();
        $dataGridBuilder = new DataGridBuilder('AdminNewsletterCampaignsGrid');
        $dataGridBuilder->setValueConversion(['status' => [
            '0' => trans('disabled'),
            '1' => trans('active')
        ]]);
        $dataGridBuilder->setCreateNewText(trans('create.new.newsletter.campaign'));
        // $dataGridBuilder->setDataGridId('AdminOpenGraphsGrid');
        $dataGridBuilder->setPrimaryRepository($repo);
        $dataGrid = $dataGridBuilder->getDataGrid();
        $dataGrid->setListActionUrl($this->getUrl()->getHttpDomain().'/admin/newsletter/campaigns/list');
        $dataGrid->setNewActionUrl($this->getUrl()->getHttpDomain().'/admin/newsletter/campaign/new');
        $dataGrid->setEditActionUrl($this->getUrl()->getHttpDomain().'/admin/newsletter/campaign/edit');
        $dataGrid->setDeleteActionUrl($this->getUrl()->getHttpDomain().'/admin/newsletter/campaign/delete');
        // $dataGrid->setJavaScriptOnDeleteConfirmed('OpenGraphImageHandler.loadGallery();');
        $response = $dataGrid->render();

        return $widgetResponse ? $this->widgetResponse($response) : $response;
    }

    /**
    * name: admin_newsletter_campaign_new, paramChain: /admin/newsletter/campaign/new
    */
    public function adminNewsletterCampaignNewAction($widgetResponse = true)
    {
        return $this->adminNewsletterCampaignEditAction(true);
    }

    /**
    * Route: [name: admin_newsletter_campaign_edit, paramChain: /admin/newsletter/campaign/edit]
    */
    public function adminNewsletterCampaignEditAction($new = false)
    {
        $id = $new ? null : $this->getContainer()->getRequest()->get('id');
        
        // $submitted = $this->getContainer()->getRequest()->getAll() == ['id' => null] ? false : true;
        $submitted = array_keys($this->getContainer()->getRequest()->getAll()) == ['id'] ? false : true;
        // $submitted = is_array($this->getContainer()->getRequest()->getAll()) ? true : false;

        $form = $this->getAdminNewsletterCampaignEditForm($id, $submitted);

        $this->wireService('NewsletterPackage/entity/Newsletter');
        $this->setService('NewsletterPackage/repository/NewsletterRepository');
        $newsletterRepo = $this->getService('NewsletterRepository');
        $newsletters = $newsletterRepo->findBy(['conditions' => [
            ['key' => 'status', 'value' => Newsletter::STATUS_ACTIVE]
        ]]);

        $viewPath = 'framework/packages/NewsletterPackage/view/widget/AdminNewsletterCampaignsWidget/edit.php';
        $response = [
            'view' => $this->renderWidget('AdminNewsletterCampaignsWidget_edit', $viewPath, [
                'id' => $id,
                'isEditable' => $form->getEntity()->getRepository()->isEditable($form->getEntity()->getId()),
                'newsletters' => $newsletters,
                'submitted' => $submitted,
                'container' => $this->getContainer(),
                'posts' => $this->getRequest()->getAll(),
                'statuses' => $this->getNewsletterService()->getNewsletterCampaignStatuses(),
                'form' => $form 
            ]),
            'data' => [
                'label' => $id ? trans('edit.newsletter.campaign') : trans('create.new.newsletter.campaign'),
                'formIsValid' => $form->isValid()
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function getAdminNewsletterCampaignEditForm($id = null, $submitted = false)
    {
        $this->wireService('FormPackage/service/FormBuilder');
        // dump($this->getContainer()->getRequest()->getAll());exit;
        $formBuilder = new FormBuilder();
        $formBuilder->setSchemaPath('NewsletterPackage/form/EditNewsletterCampaignSchema');
        $formBuilder->setPackageName('NewsletterPackage');
        $formBuilder->setSubject('EditNewsletterCampaign');
        $formBuilder->setPrimaryKeyValue($id);
        $formBuilder->addExternalPost('id');
        $formBuilder->setAutoSubmit(false);
        $formBuilder->setSubmitted($submitted);
        $form = $formBuilder->createForm();
        return $form;
    }

    /**
    * name: admin_newsletter_campaign_delete, paramChain: /admin/newsletter/campaign/delete
    */
    public function adminNewsletterCampaignDeleteAction()
    {
        $repo = $this->getRepository();
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
}