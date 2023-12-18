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

class NewsletterWidgetController extends WidgetController
{
    public function getNewsletterService()
    {
        $this->setService('NewsletterPackage/service/NewsletterService');
        return $this->getService('NewsletterService');
    }

    public function getRepository()
    {
        $this->setService('NewsletterPackage/repository/NewsletterRepository');
        return $this->getService('NewsletterRepository');
    }
    
    /**
    * Route: [name: admin_newsletters_widget}, paramChain: /admin/newsletters/widget]
    */
    public function adminNewslettersWidgetAction()
    {
        $viewPath = 'framework/packages/NewsletterPackage/view/widget/AdminNewslettersWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminNewslettersWidget', $viewPath, [
                'container' => $this->getContainer(),
            ]),
            'data' => []
        ];
        
        return $this->widgetResponse($response);
    }

    /**
    * name: admin_newsletters_list, paramChain: /admin/newsletters/list
    */
    public function adminNewslettersListAction($widgetResponse = true)
    {
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $repo = $this->getRepository();
        $dataGridBuilder = new DataGridBuilder('AdminNewslettersGrid');
        $dataGridBuilder->setValueConversion(['status' => [
            '0' => trans('disabled'),
            '1' => trans('active')
        ]]);
        $dataGridBuilder->setCreateNewText(trans('create.new.newsletter'));
        // $dataGridBuilder->setDataGridId('AdminOpenGraphsGrid');
        $dataGridBuilder->setPrimaryRepository($repo);
        $dataGrid = $dataGridBuilder->getDataGrid();
        $dataGrid->setListActionUrl($this->getUrl()->getHttpDomain().'/admin/newsletters/list');
        $dataGrid->setNewActionUrl($this->getUrl()->getHttpDomain().'/admin/newsletter/new');
        $dataGrid->setEditActionUrl($this->getUrl()->getHttpDomain().'/admin/newsletter/edit');
        $dataGrid->setDeleteActionUrl($this->getUrl()->getHttpDomain().'/admin/newsletter/delete');
        // $dataGrid->setJavaScriptOnDeleteConfirmed('OpenGraphImageHandler.loadGallery();');
        $response = $dataGrid->render();

        return $widgetResponse ? $this->widgetResponse($response) : $response;
    }

    /**
    * name: admin_newsletter_new, paramChain: /admin/newsletter/new
    */
    public function adminNewsletterNewAction($widgetResponse = true)
    {
        return $this->adminNewsletterEditAction(true);
    }

    /**
    * Route: [name: admin_newsletter_edit, paramChain: /admin/newsletter/edit]
    */
    public function adminNewsletterEditAction($new = false)
    {
        $id = $new ? null : $this->getContainer()->getRequest()->get('id');
        
        $submitted = array_keys($this->getContainer()->getRequest()->getAll()) == ['id'] ? false : true;
        // $submitted = is_array($this->getContainer()->getRequest()->getAll()) ? true : false;

        $form = $this->getAdminNewsletterEditForm($id, $submitted);

        $viewPath = 'framework/packages/NewsletterPackage/view/widget/AdminNewslettersWidget/edit.php';
        $response = [
            'view' => $this->renderWidget('AdminNewslettersWidget_edit', $viewPath, [
                'id' => $id,
                'isEditable' => $form->getEntity()->getRepository()->isEditable($form->getEntity()->getId()),
                'submitted' => $submitted,
                'container' => $this->getContainer(),
                'posts' => $this->getRequest()->getAll(),
                'statuses' => $this->getNewsletterService()->getNewsletterStatuses(),
                'form' => $form 
            ]),
            'data' => [
                'label' => $id ? trans('edit.newsletter') : trans('create.new.newsletter'),
                'submitted' => $submitted,
                'formIsValid' => $form->isValid()
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function getAdminNewsletterEditForm($id = null, $submitted = false)
    {
        $this->wireService('FormPackage/service/FormBuilder');
        $formBuilder = new FormBuilder();
        $formBuilder->setSchemaPath('NewsletterPackage/form/EditNewsletterSchema');
        $formBuilder->setPackageName('NewsletterPackage');
        $formBuilder->setSubject('EditNewsletter');
        $formBuilder->setPrimaryKeyValue($id);
        $formBuilder->addExternalPost('id');
        $formBuilder->setAutoSubmit(false);
        $formBuilder->setSubmitted($submitted);
        $form = $formBuilder->createForm();
        return $form;
    }

    /**
    * name: admin_newsletter_delete, paramChain: /admin/newsletter/delete
    */
    public function adminNewsletterDeleteAction()
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