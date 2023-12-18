<?php
namespace framework\packages\WordClearingPackage\controller;

use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\component\parent\ImageResponse;
use framework\packages\ToolPackage\service\Uploader;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\WordClearingPackage\repository\WordExplanationRepository;
use framework\packages\DataGridPackage\service\DataGridBuilder;
use framework\packages\WordClearingPackage\service\WordExplanationService;

class WordClearingWidgetController extends WidgetController
{
    /**
    * name: ajax_wordExplanation, paramChain: /ajax/wordExplanation
    */
    public function wordExplanationAction()
    {
        $this->getContainer()->wireService('WordClearingPackage/service/WordExplanationService');
        $wordExplanationService = new WordExplanationService();

        $response = [
            'view' => $wordExplanationService->getWordExplanationText($this->getRequest()->get('keyText')),
            // 'view' => $this->renderWidget('EditWordExplanationForm', $viewPath, [
            //     'container' => $this->getContainer(),
            //     'form' => $form
            // ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: admin_wordExplanationWidget, paramChain: /admin/wordExplanationWidget
    */
    public function adminWordExplanationWidgetAction()
    {
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $this->setService('WordClearingPackage/repository/WordExplanationRepository');
        $repo = $this->getService('WordExplanationRepository');
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $dataGridBuilder = new DataGridBuilder('AdminWordExplanationDataGrid');
        // $dataGridBuilder->setDeleteDisabled(true);
        // $repo->setProperties(['id', 'title', 'description']);
        $dataGridBuilder->setPrimaryRepository($repo);
        // dump('alma');exit;
        $dataGrid = $dataGridBuilder->getDataGrid();
        // dump($dataGrid);exit;
        $response = $dataGrid->render();
        // dump($response);exit;

        // $formBuilder = new FormBuilder();
        // $formBuilder->setPackageName('AppearancePackage');
        // $formBuilder->setSubject('openGraph');
        // $formBuilder->addExternalPost('id');
        // $form = $formBuilder->createForm();
        // $viewPath = 'framework/packages/AppearancePackage/view/widget/AdminOpenGraphWidget/widget.php';
        // $response = [
        //     'view' => $this->renderWidget('AdminOpenGraphWidget', $viewPath, [
        //         'container' => $this->getContainer(),
        //         'form' => $form
        //     ]),
        //     'data' => []
        // ];

        return $this->widgetResponse($response);
    }

    /**
    * name: document_wordExplanation_widget, paramChain: /document/wordExplanation/widget
    */
    public function documentWordExplanationWidgetAction()
    {
        $viewPath = 'framework/packages/WordClearingPackage/view/widget/DocumentWordExplanationWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('DocumentWordExplanationWidget', $viewPath, [
                'container' => $this->getContainer(),
                'documentTitle' => '',
                'message' => ''
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: admin_wordExplanation_new, paramChain: /admin/wordExplanation/new
    */
    public function adminWordExplanationNewAction()
    {
        return $this->adminWordExplanationEditAction();
    }

    /**
    * Route: [name: admin_wordExplanation_edit, paramChain: /admin/wordExplanation/edit]
    */
    public function adminWordExplanationEditAction()
    {
        $this->wireService('WordClearingPackage/entity/WordExplanation');
        $this->wireService('FormPackage/service/FormBuilder');
        $id = (int)$this->getContainer()->getRequest()->get('id');
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('WordClearingPackage');
        $formBuilder->setSubject('editWordExplanation');
        $formBuilder->setPrimaryKeyValue($id);
        $formBuilder->addExternalPost('id');
        $form = $formBuilder->createForm();
        // dump($form);exit;
        $viewPath = 'framework/packages/WordClearingPackage/view/widget/AdminWordExplanationWidget/editWordExplanation.php';
        $response = [
            'view' => $this->renderWidget('EditWordExplanationForm', $viewPath, [
                'container' => $this->getContainer(),
                'form' => $form
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
    * name: admin_wordExplanation_delete, paramChain: /admin/wordExplanation/delete
    */
    public function adminWordExplanationDeleteAction()
    {
        $this->getContainer()->wireService('WordClearingPackage/repository/WordExplanationRepository');
        $repo = new WordExplanationRepository();
        $repo->remove($this->getContainer()->getRequest()->get('id'));

        $response = [
            'view' => ''
        ];

        return $this->widgetResponse($response);
    }
}
