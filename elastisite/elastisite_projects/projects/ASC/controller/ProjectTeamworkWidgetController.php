<?php
namespace projects\ASC\controller;

use App;
use framework\component\parent\WidgetController;
use framework\kernel\view\ViewRenderer;
use framework\packages\ContentCapturePackage\service\ContentProcessor;
use framework\packages\ContentCapturePackage\service\CurlHelper;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\ToolPackage\service\Mailer;
use hexydec\html\htmldoc;
use projects\ASC\repository\AscScaleRepository;
use projects\ASC\repository\AscUnitRepository;
use projects\ASC\repository\ProjectTeamRepository;
use projects\ASC\repository\ProjectTeamUserRepository;
use projects\ASC\service\AscPermissionService;
use projects\ASC\service\AscRequestService;
use projects\ASC\service\AscUnitBuilderService;
use projects\ASC\service\ProjectTeamworkService;
use projects\ASC\service\ProjectUserService;

class ProjectTeamworkWidgetController extends WidgetController
{
    /**
     * Notice that this widget is not working, instead this page loads AscScaleBuilderWidget, 
     * and that widget controller determines that this is a projectTeamwork request.
     * 
     * Route: [name: widget_ProjectTeamworkWidget, paramChain: /widget/ProjectTeamworkWidget]
     */
    // public function projectTeamworkWidgetAction()
    // {
    //     // dump();exit;
    //     $viewPath = 'projects/ASC/view/widget/ProjectTeamworkWidget/widget.php';

    //     $response = [
    //         'view' => $this->renderWidget('ProjectTeamworkWidget', $viewPath, [
    //             // 'container' => $this->getContainer(),
    //             'documentTitle' => '',
    //             'message' => ''
    //         ]),
    //         'data' => []
    //     ];

    //     // dump($response);exit;

    //     return $this->widgetResponse($response);
    // }

    /**
     * There will be only 2 views here, because we shouldn't recreate subjectPanel and controlPanel,
     * only the projectTeamworkView and the modal.
    */
    public function createViews(
        $getProjectTeamworkView = true, 
        $modalActionType = 'edit', 
        $getEditProjectTeamModalView = false, 
        $getEditProjectTeamInviteModalView = false,
        $getEditProjectTeamUserModalView = false
    )
    {
        App::getContainer()->wireService('projects/ASC/service/AscRequestService');
        $processedRequestData = AscRequestService::getProcessedRequestData();
        $callback = null;
        $closeModal = false;

        $renderedEditProjectTeamModalView = null;
        if ($getEditProjectTeamModalView) {
            $editProjectTeamModalViewArray = self::createEditProjectTeamModalView($processedRequestData, $modalActionType);
            $renderedEditProjectTeamModalView = $editProjectTeamModalViewArray['renderedView'];
            $closeModal = $editProjectTeamModalViewArray['data']['closeModal'];
            $callback = $modalActionType.'ProjectTeamCallback';
        }

        $renderedEditProjectTeamInviteModalView = null;
        if ($getEditProjectTeamInviteModalView) {
            $editProjectTeamInviteModalViewArray = self::createEditProjectTeamInviteModalView($processedRequestData, $modalActionType);
            $renderedEditProjectTeamInviteModalView = $editProjectTeamInviteModalViewArray['renderedView'];
            $closeModal = $editProjectTeamInviteModalViewArray['data']['closeModal'];
            $callback = $modalActionType.'ProjectTeamInviteCallback';
        }

        $renderedEditProjectTeamUserModalView = null;
        if ($getEditProjectTeamUserModalView) {
            $editProjectTeamUserModalViewArray = self::createEditProjectTeamUserModalView($processedRequestData, $modalActionType);
            $renderedEditProjectTeamUserModalView = $editProjectTeamUserModalViewArray['renderedView'];
            $closeModal = $editProjectTeamUserModalViewArray['data']['closeModal'];
            $callback = $modalActionType.'ProjectTeamUserCallback';
        }

        $renderedProjectTeamworkView = null;
        if ($getProjectTeamworkView) {
            $projectTeamworkViewArray = self::createProjectTeamworkView($processedRequestData);
            $renderedProjectTeamworkView = $projectTeamworkViewArray['renderedView'];
        }

        return [
            'views' => [
                'ProjectTeamworkView' => $renderedProjectTeamworkView,
                'EditProjectTeamModalView' => $renderedEditProjectTeamModalView,
                'EditProjectTeamUserModalView' => $renderedEditProjectTeamUserModalView,
                'EditProjectTeamInviteModalView' => $renderedEditProjectTeamInviteModalView
            ],
            'data' => [
                'closeModal' => $closeModal,
                'callback' => $callback
            ]
        ];
    }

    /**
    * Route: [name: projectTeamwork_newProjectTeam, paramChain: /projectTeamwork/newProjectTeam]
    */
    public function newProjectTeamAction()
    {
        return $this->editProjectTeamAction(true);
    }

    /**
    * Route: [name: projectTeamwork_editProjectTeam, paramChain: /projectTeamwork/editProjectTeam]
    */
    public function editProjectTeamAction($new = false)
    {
        $response = $this->createViews(true, ($new ? 'new' : 'edit'), true, false);

        return $this->widgetResponse($response);
    }

    public static function createEditProjectTeamModalView($processedRequestData, $modalActionType)
    {
        $form = self::getEditProjectTeamForm();

        if (App::getContainer()->getRequest()->get('submitted') == 'false') {
            $form->setSubmitted(false);
        }

        // dump($form);exit;

        // $ascUnitIdPost = App::getContainer()->getRequest()->get('ASC_editProjectTeam_AscUnit_ascUnitId');
        // if ($form->isSubmitted() && !empty($ascUnitIdPost)) {
        //     $postedAscScale = $form->getEntity()->getAscUnit()->getAscScale(); 
        //     if ($postedAscScale && $postedAscScale->getId() && ($postedAscScale->getId() != $form->getEntity()->getAscScale()->getId())) {
        //         // dump($postedAscScale->getId());
        //         // dump($form->getEntity()->getAscScale()->getId());
        //         // dump($form);
        //         $form->addMessage('ASC_editProjectTeam_AscUnit_ascUnitId', trans('invalid.unit'));
        //         $form->setValid(false);
        //     }
        // }

        $postedAscUnitId = App::getContainer()->getRequest()->get('ASC_editProjectTeam_ascUnitId');
        $subject = App::getContainer()->getRequest()->get('ASC_editProjectTeam_subject');
        $validAscUnit = true;
        // dump($form->isSubmitted());
        // dump($postedAscUnitId);exit;
        $ascUnit = $form->getEntity()->getAscUnit();
        $ascScale = $form->getEntity()->getAscScale();
        // dump($ascUnit);exit;
        $postedAscUnit = null;
        if ($form->isSubmitted() && !empty($postedAscUnitId)) {
            $validAscUnit = false;
            App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
            $ascUnitRepo = new AscUnitRepository();
            $postedAscUnit = $ascUnitRepo->find($postedAscUnitId);
            if ($postedAscUnit) {
                $postedAscScale = $postedAscUnit->getAscScale(); 
                if ($postedAscScale && $postedAscScale->getId() && ($postedAscScale->getId() == $ascScale->getId())) {
                    // dump($postedAscScale->getId());
                    // dump($form->getEntity()->getAscScale()->getId());
                    // dump($form);
                    $validAscUnit = true;
                }
            }

            if (!$validAscUnit) {
                $form->addMessage('ASC_editProjectTeam_ascUnitId', trans('invalid.unit'));
                $form->setValid(false);
                $postedAscUnit = null;
            }

            // dump($postedAscUnit);
            // dump($validAscUnit); exit;
            if ($postedAscUnit) {
                $ascUnit = $postedAscUnit;
            }
        }

        $projectTeam = $form->getEntity();
        if ($form->isSubmitted() && $form->isValid()) {
            // $ascUnitId = $form->getValueCollector()->getDisplayed('ascUnitId');

            // dump($ascUnitId);
            // dump($form->getEntity());exit;
            $projectTeam->setAscScale($processedRequestData['ascScale']);
            $projectTeam->setAscUnit($postedAscUnit);
            $projectTeam = $projectTeam->getRepository()->store($form->getEntity());
        }

        if (!$subject && $ascUnit) {
            $subject = $ascUnit->getSubject();
        }

        $viewPath = 'projects/ASC/view/AscScaleBuilder/ProjectTeamwork/editProjectTeamModal.php';
        $view = ViewRenderer::renderWidget('AscScaleBuilderWidget_ProjectTeamwork_editProjectTeamModal', $viewPath, [
            'form' => $form,
            'subject' => $subject,
            'ascUnitId' =>  $ascUnit ? $ascUnit->getId() : null,
            'ascUnitOptions' => self::getAscUnitOptions($processedRequestData['scaleId'], $ascUnit->getId(), (empty($subject) ? null : $subject)),
            'modalActionType' => $modalActionType
        ]);

        return [
            'renderedView' => $view,
            'data' => [
                'closeModal' => $form->isValid() && $form->isSubmitted() ? true : false
            ]
        ];
    }

    public static function getAscUnitOptions(int $ascScaleId, int $ascUnitId = null, string $subject = null)
    {
        if (!$ascScaleId) {
            return [];
        }

        $return = [];

        App::getContainer()->wireService('projects/ASC/service/AscUnitBuilderService');

        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $ascUnitRepo = new AscUnitRepository();

        $ascUnitIdsForOptions = $ascUnitRepo->getAscUnitIdsForOptions($ascScaleId, $subject);
        if ($ascUnitId && !in_array($ascUnitId, $ascUnitIdsForOptions)) {
            $ascUnitIdsForOptions[] = $ascUnitId;
        }

        foreach ($ascUnitIdsForOptions as $resultAscUnitId) {
            $ascUnit = $ascUnitRepo->find($resultAscUnitId);
            if ($ascUnit) {
                $unitData = AscUnitBuilderService::createUnitDataFromObject($ascUnit);
                // $unitData['data']['mainEntryTitle'];
                $translatedSubjectSingular = $unitData['data']['translatedSubjectSingular'];
                $mainEntryTitle = $unitData['data']['mainEntryTitle'];
                if (!empty($mainEntryTitle) && !empty($translatedSubjectSingular)) {
                    $return[$resultAscUnitId] = '['.$translatedSubjectSingular.'] '.$unitData['data']['mainEntryTitle'];
                }
            }
        }

        // dump($projectTeamId);
        // dump($ascScaleId);
        // dump($ascUnitId);
        // dump($subject);
        // exit;
        return $return;
    }

    public static function getEditProjectTeamForm()
    {
        App::getContainer()->wireService('FormPackage/service/FormBuilder');
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('ASC');
        $formBuilder->setSubject('editProjectTeam');
        $formBuilder->setSchemaPath('projects/ASC/form/EditProjectTeamSchema');
        $formBuilder->setPrimaryKeyValue(App::getContainer()->getRequest()->get('id'));
        $formBuilder->addExternalPost('id');
        $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();

        return $form;
    }

    /**
    * Route: [name: projectTeamwork_editProjectTeam, paramChain: /projectTeamwork/deleteProjectTeam]
    */
    public function deleteProjectTeamAction()
    {
        $response = [
            'view' => '',
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    // User

    /**
    * Route: [name: projectTeamwork_newProjectTeamUser, paramChain: /projectTeamwork/newProjectTeamUser]
    */
    public function newProjectTeamUserAction()
    {
        return $this->editProjectTeamUserAction(true);
    }

    /**
    * Route: [name: projectTeamwork_editProjectTeamUser, paramChain: /projectTeamwork/editProjectTeamUser]
    */
    public function editProjectTeamUserAction($new = false)
    {
        $response = $this->createViews(true, ($new ? 'new' : 'edit'), false, false, true);

        return $this->widgetResponse($response);
    }

    public static function createEditProjectTeamUserModalView($processedRequestData, $modalActionType)
    {
        $form = self::getEditProjectTeamUserForm();
        $projectTeamId = (int)App::getContainer()->getRequest()->get('projectTeamId');
        App::getContainer()->wireService('projects/ASC/repository/ProjectTeamRepository');
        $projectTeamRepository = new ProjectTeamRepository();
        $projectTeam = $projectTeamRepository->find($projectTeamId);
        if (!$projectTeam) {
            throw new \Exception('Invalid project team');
        }
        // dump(App::getContainer()->getRequest()->getAll());
        // dump($form);exit;
        if ($form->isSubmitted() && $form->isValid()) {
            $projectTeamUser = $form->getEntity();
            $projectTeamUser->setProjectTeam($projectTeam);
            // dump($projectTeamUser);exit;
            // $projectTeamUser->setAscScale($processedRequestData['ascScale']);
            // $projectTeamUser->setAscUnit($processedRequestData['ascUnit']);
            $projectTeamUser->getRepository()->store($form->getEntity());
        }
        // if ($form->isSubmitted()) {
        //     dump('isSubmitted');exit;
        // }

        $viewPath = 'projects/ASC/view/AscScaleBuilder/ProjectTeamwork/editProjectTeamUserModal.php';
        $view = ViewRenderer::renderWidget('AscScaleBuilderWidget_ProjectTeamwork_editProjectTeamUserModal', $viewPath, [
            'form' => $form,
            'projectTeamId' => $projectTeamId,
            'projectUserSelectOptions' => ProjectUserService::getProjectUserSelectOptions(),
            'modalActionType' => $modalActionType
        ]);

        return [
            'renderedView' => $view,
            'data' => [
                'closeModal' => $form->isValid() ? true : false
            ]
        ];
    }

    public static function getEditProjectTeamUserForm()
    {
        App::getContainer()->wireService('FormPackage/service/FormBuilder');
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('ASC');
        $formBuilder->setSubject('editProjectTeamUser');
        $formBuilder->setSchemaPath('projects/ASC/form/EditProjectTeamUserSchema');
        $formBuilder->setPrimaryKeyValue(App::getContainer()->getRequest()->get('id'));
        $formBuilder->addExternalPost('id');
        $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();

        return $form;
    }

    // Invite

    /**
    * Route: [name: projectTeamwork_newProjectTeamInvite, paramChain: /projectTeamwork/newProjectTeamInvite]
    */
    public function newProjectTeamInviteAction()
    {
        return $this->editProjectTeamInviteAction(true);
    }

    /**
    * Route: [name: projectTeamwork_editProjectTeamInvite, paramChain: /projectTeamwork/editProjectTeamInvite]
    */
    public function editProjectTeamInviteAction($new = false)
    {
        $response = $this->createViews(true, ($new ? 'new' : 'edit'), false, true);

        return $this->widgetResponse($response);
    }

    public static function createEditProjectTeamInviteModalView($processedRequestData, $modalActionType)
    {
        $form = self::getEditProjectTeamInviteForm();
        // dump(App::getContainer()->getRequest()->getAll());
        // dump($form->getEntity());exit;
        if ($form->isSubmitted() && $form->isValid()) {
            $projectTeamInvite = $form->getEntity();
            $projectTeamInvite->setAscScale($processedRequestData['ascScale']);
            $projectTeamInvite->setAscUnit($processedRequestData['ascUnit']);
            $projectTeamInvite->getRepository()->store($form->getEntity());
        }
        // if ($form->isSubmitted()) {
        //     // dump($alma);exit;
        // }

        $viewPath = 'projects/ASC/view/AscScaleBuilder/ProjectTeamwork/editProjectTeamInviteModal.php';
        $view = ViewRenderer::renderWidget('AscScaleBuilderWidget_ProjectTeamwork_editProjectTeamInviteModal', $viewPath, [
            'form' => $form,
            'modalActionType' => $modalActionType
        ]);

        return [
            'renderedView' => $view,
            'data' => [
                'closeModal' => $form->isValid() ? true : false
            ]
        ];
    }

    public static function getEditProjectTeamInviteForm()
    {
        App::getContainer()->wireService('FormPackage/service/FormBuilder');
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('ASC');
        $formBuilder->setSubject('editProjectTeamInvite');
        $formBuilder->setSchemaPath('projects/ASC/form/EditProjectTeamInviteSchema');
        $formBuilder->setPrimaryKeyValue(App::getContainer()->getRequest()->get('id'));
        $formBuilder->addExternalPost('id');
        $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();

        return $form;
    }

    /**
     * The main content view
    */

    public static function createProjectTeamworkView($processedRequestData, $modalActionType = 'edit')
    {
        $ascScale = $processedRequestData['ascScale'];

        if (!$ascScale) {
            return null;
        }

        App::getContainer()->wireService('projects/ASC/service/AscPermissionService');
        $scalePermissions = AscPermissionService::getCurrentScaleAccessibility();

        // dump($scalePermissions);exit;
        // App::getContainer()->wireService('projects/ASC/repository/AscScaleRepository');
        // $ascScaleRepo = new AscScaleRepository();

        App::getContainer()->wireService('projects/ASC/service/ProjectUserService');
        $projectUser = ProjectUserService::getProjectUser();
        $projectUserId = $projectUser ? $projectUser->getId() : null;

        App::getContainer()->wireService('projects/ASC/service/ProjectTeamworkService');
        $projectTeamworkData = ProjectTeamworkService::getProjectTeamworkData($ascScale, $projectUserId);


        $viewPath = 'projects/ASC/view/AscScaleBuilder/ProjectTeamwork/ProjectTeamwork.php';
        $view = ViewRenderer::renderWidget('AscScaleBuilderWidget_ProjectTeamwork', $viewPath, [
            'ascScale' => $ascScale,
            'scalePermissions' => $scalePermissions,
            'modalActionType' => $modalActionType,
            // 'scaleOwnerData' => ProjectTeamworkService::getScaleOwnerData($ascScale),
            'projectTeamworkData' => $projectTeamworkData
        ]);

        return [
            'renderedView' => $view,
            'data' => [
            ]
        ];
    }
}
