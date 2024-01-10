<?php
namespace framework\packages\SurveyPackage\controller;

use App;
use framework\component\helper\PHPHelper;
use framework\component\parent\WidgetController;
use framework\packages\DataGridPackage\service\DataGridBuilder;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\SurveyPackage\entity\Survey;
use framework\packages\SurveyPackage\entity\SurveyOption;
use framework\packages\SurveyPackage\entity\SurveyQuestion;
use framework\packages\SurveyPackage\repository\SurveyCompletionRepository;
use framework\packages\SurveyPackage\repository\SurveyOptionRepository;
use framework\packages\SurveyPackage\repository\SurveyQuestionRepository;
use framework\packages\SurveyPackage\repository\SurveyRepository;
use framework\packages\SurveyPackage\service\SurveyService;

// use framework\packages\UserPackage\repositorx\TemporaryAccountRepository;
// use framework\packages\UserPackage\repositorx\TemporaryPersonRepository;
// use framework\packages\WebshopPackage\repository\ProductImageRepository;

class SurveyWidgetController extends WidgetController
{
    /**
    * Route: [name: survey_answerForm, paramChain: /survey/answerForm]
    */
    public function surveyAnswerFormAction()
    {
        $slug = App::getContainer()->getUrl()->getSubRoute();
        // dump($alma);exit;
        $this->setService('SurveyPackage/entity/Survey');
        $this->setService('SurveyPackage/service/SurveyService');
        $survey = SurveyService::getSurveyBySlug($slug);

        // dump($survey); exit;
        if (!$survey || $survey->getStatus() != Survey::STATUS_ACTIVE) {
            PHPHelper::redirect('/404', 'SurveyWidgetController/surveyAnswerFormAction()');
        }

        $processedSurveyAndwers = SurveyService::processSurveyCompletion($survey);
        $surveyFormViewName = $this->getSurveyFormViewName($survey->getId());
        $viewPath = 'framework/packages/SurveyPackage/view/widget/FillSurveyWidget/'.$surveyFormViewName.'.php';
        $response = [
            'view' => $this->renderWidget('FillSurveyWidget', $viewPath, [
                'httpDomain' => $this->getUrl()->getHttpDomain(),
                'survey' => $survey,
                'request' => App::getContainer()->getRequest()->getAll(),
                'missingAnswers' => $processedSurveyAndwers['missingAnswers'],
                'postedAnswers' => $processedSurveyAndwers['postedAnswers']
                // 'surveyId' => $surveyId,
                // 'surveyQuestions' => $surveyQuestions
            ]),
            'data' => [
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: survey_FillSurveyWidget, paramChain: /survey/FillSurveyWidget]
    */
    public function fillSurveyWidgetAction()
    {
        $slug = App::getContainer()->getUrl()->getSubRoute();
        // dump($alma);exit;
        $this->setService('SurveyPackage/entity/Survey');
        $this->setService('SurveyPackage/service/SurveyService');
        $survey = SurveyService::getSurveyBySlug($slug);

        // App::getContainer()->getSession()->set('surveyFilled_'.$survey->getId(), false);

        // dump($survey); exit;
        if (!$survey || $survey->getStatus() != Survey::STATUS_ACTIVE) {
            PHPHelper::redirect('/404', 'SurveyWidgetController/fillSurveyWidgetAction()');
        }
        
        $surveyFormViewName = $this->getSurveyFormViewName($survey->getId());
        $viewPath = 'framework/packages/SurveyPackage/view/widget/FillSurveyWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('FillSurveyWidget', $viewPath, [
                'httpDomain' => $this->getUrl()->getHttpDomain(),
                'survey' => $survey,
                'request' => null,
                'missingAnswers' => [],
                'postedAnswers' => [],
                'surveyFormViewName' => $surveyFormViewName
                // 'surveyId' => $surveyId,
                // 'surveyQuestions' => $surveyQuestions
            ]),
            'data' => [
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function getSurveyFormViewName($surveyId)
    {
        return SurveyService::isSurveyFilled($surveyId) ? 'success' : 'form';
    }

    /**
    * Route: [name: admin_survey_surveysWidget, paramChain: /admin/surveysWidget]
    */
    public function adminSurveysWidgetAction()
    {
        return $this->renderSurveysList();
    }

    public function renderSurveysList()
    {
        $this->setService('SurveyPackage/repository/SurveyRepository');
        // $this->setService('WebshopPackage/service/WebshopService');
        // $webshopService = $this->getService('WebshopService');
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $dataGridBuilder = new DataGridBuilder('AdminSurveysDataGrid');
        $dataGridBuilder->setDeleteDisabled(false);
        $dataGridBuilder->setValueConversion(['status' => [
            '0' => trans('disabled'),
            '1' => trans('active'),
            '2' => trans('closed')
        ]]);
        // $dataGridBuilder->setValueConversion(['status' => [
        //     '0' => trans('disabled'),
        //     '1' => trans('active')
        // ]]);

        $dataGridBuilder->setPrimaryRepository($this->getService('SurveyRepository'));
        $dataGrid = $dataGridBuilder->getDataGrid();
        $dataGrid->setNewActionUrl('/admin/survey/newSurvey');
        $dataGrid->setEditActionUrl($this->getUrl()->getHttpDomain().'/admin/survey/editSurvey');
        $dataGrid->setDeleteActionUrl($this->getUrl()->getHttpDomain().'/admin/survey/deleteSurvey');
        $response = $dataGrid->render();

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_survey_surveysList, paramChain: /admin/surveysList]
    */
    // public function adminSurveysListAction()
    // {
    //     return $this->renderSurveysList();
    // }

    /**
    * Route: [name: admin_survey_getAnswersView, paramChain: /admin/survey/getAnswersViewy]
    */
    public function adminSurveyGetAnswersViewAction()
    {
        // $this->setService('SurveyPackage/entity/Survey');
        // $this->setService('SurveyPackage/repository/SurveyRepository');
        // $surveyRepo = new SurveyRepository();
        // $survey = $surveyRepo->find((int)App::getContainer()->getRequest()->get('surveyId'));
        $this->setService('SurveyPackage/entity/Survey');
        $this->setService('SurveyPackage/service/SurveyService');
        $survey = SurveyService::getSurvey((int)App::getContainer()->getRequest()->get('surveyId'));

        // dump($survey); exit;
        if (!$survey) {
            PHPHelper::redirect('/404', 'SurveyWidgetController/adminSurveyGetAnswersViewAction()');
        }

        $this->setService('SurveyPackage/repository/SurveyCompletionRepository');
        $surveyCompletionRepo = new SurveyCompletionRepository();
        $surveyCompletions = $surveyCompletionRepo->findBy(['conditions' => [
            ['key' => 'survey_id', 'value' => $survey->getId()]
        ]]);

        // dump($surveyCompletions);exit;

        $viewPath = 'framework/packages/SurveyPackage/view/widget/AdminSurveysWidget/answersView.php';
        $response = [
            'view' => $this->renderWidget('AdminSurveysWidget_answersView', $viewPath, [
                'httpDomain' => $this->getUrl()->getHttpDomain(),
                'survey' => $survey,
                'surveyCompletions' => $surveyCompletions
                // 'surveyId' => $surveyId,
                // 'surveyQuestions' => $surveyQuestions
            ]),
            'data' => [
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_survey_newSurvey, paramChain: /admin/survey/newSurvey]
    */
    public function adminNewSurveyAction()
    {
        return $this->adminEditSurveyAction();
    }

    /**
    * Route: [name: admin_survey_editSurvey, paramChain: /admin/survey/editSurvey]
    */
    public function adminEditSurveyAction()
    {
        return $this->adminEditSurveyContentAction('full');
    }

    /**
    * Route: [name: admin_survey_editSurveyFlexibleContent, paramChain: /admin/survey/editSurveyFlexibleContent]
    */
    public function adminEditSurveyContentAction($contentDepth = 'flexible')
    {
        $id = $this->getContainer()->getRequest()->get('id');
        $this->setService('SurveyPackage/repository/SurveyRepository');
        $surveyRepo = $this->getService('SurveyRepository');
        $survey = $id ? $surveyRepo->find($id) : null;

        // dump($id);exit;

        if ($id && !$survey) {
            return null;
        }

        // return $survey ? $this->editSurvey($contentDepth, $survey) : $this->newSurvey($contentDepth);

        $this->wireService('FormPackage/service/FormBuilder');
        // dump($this->getContainer()->getRequest()->getAll());exit;
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('SurveyPackage');
        $formBuilder->setSubject('editSurvey');
        $formBuilder->setPrimaryKeyValue($id);
        // $formBuilder->addExternalPost('id');
        // $formBuilder->addExternalPost('FrameworkPackage_pageEdit_file');
        $formBuilder->setSaveRequested($id ? false : true);
        // $formBuilder->setAutoSubmit(false);
        $formBuilder->setSubmitted($this->getContainer()->getRequest()->get('submitted') ? : false);
        $form = $formBuilder->createForm();

        /**
         * Edit only! (New is saved automatically)
        */
        if ($id && $form->isSubmitted() && $form->isValid()) {
            $storedEntity = $form->getEntity()->getRepository()->store($form->getEntity());
            $form->setEntity($storedEntity);
        }
        
        $viewPath = 'framework/packages/SurveyPackage/view/widget/AdminSurveysWidget/'.($contentDepth == 'flexible' ? 'editFlexibleContent' : 'edit').'.php';
        $response = [
            'view' => $this->renderWidget('editSurvey', $viewPath, [
                'httpDomain' => $this->getUrl()->getHttpDomain(),
                'form' => $form,
                'options' => []
            ]),
            'data' => [
                'formIsValid' => $form->isValid(),
                'label' => $id ? $form->getEntity()->getTitle().'' : trans('new.survey'),
                'editedId' => $id,
                'newSaved' => !$id && $form->getEntity()->getId() ? true : false,
                'entityId' => $form->getEntity()->getId()
            ]
        ];
        
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_survey_deleteSurvey, paramChain: /admin/survey/deleteSurvey]
    */
    public function adminDeleteSurveyAction()
    {
        $this->wireService('SurveyPackage/repository/SurveyRepository');
        $repo = new SurveyRepository();
        $id = (int)$this->getContainer()->getRequest()->get('id');
        $entity = $repo->find($id);

        /**
         * Checking if user permitted this entity
        */
        if ($entity && $entity->checkCorrectWebsite() == false) {
            $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
            $securityEventHandler->addEvent('TESTING_FOREIGN_DATA', $id, 'QuestionId');
        }

        $repo->remove($id);

        $response = [
            'view' => ''
            // 'data' => [
            //     'id' => $id,
            //     // 'result' => $result
            // ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_survey_getQuestionList, paramChain: /admin/survey/getQuestionList]
    */
    public function adminGetQuestionListAction()
    {
        $this->wireService('SurveyPackage/repository/SurveyRepository');
        $surveyRepo = new SurveyRepository();
        $surveyId = (int)$this->getContainer()->getRequest()->get('surveyId');
        $survey = $surveyRepo->find($surveyId);

        if (!$survey) {
            return null;
        }

        /**
         * Checking if user permitted this entity
        */
        if ($survey && $survey->checkCorrectWebsite() == false) {
            $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
            $securityEventHandler->addEvent('TESTING_FOREIGN_DATA', $surveyId, 'SurveyId');
        }

        $this->setService('SurveyPackage/repository/SurveyQuestionRepository');
        $surveyQuestionRepo = new SurveyQuestionRepository();
        $surveyQuestions = $surveyQuestionRepo->findBy(['conditions' => [['key' => 'survey_id', 'value' => $surveyId]]]);

        $viewPath = 'framework/packages/SurveyPackage/view/widget/AdminSurveysWidget/questionList.php';
        $response = [
            'view' => $this->renderWidget('editSurvey', $viewPath, [
                'httpDomain' => $this->getUrl()->getHttpDomain(),
                'surveyId' => $surveyId,
                'surveyQuestions' => $surveyQuestions,
                'disabled' => $survey->getRepository()->isDisabled($survey->getId())
            ]),
            'data' => [
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_survey_saveQuestion, paramChain: /admin/survey/saveQuestion]
    */
    public function adminSaveQuestionAction()
    {
        $saveQuestionResult = $this->saveQuestion();
        $response = [
            'view' => '',
            'data' => $saveQuestionResult
        ];

        return $this->widgetResponse($response);
    }

    public function saveQuestion()
    {
        $success = true;
        $this->wireService('SurveyPackage/repository/SurveyQuestionRepository');
        $surveyQuestionRepo = new SurveyQuestionRepository();
        $surveyQuestionId = (int)$this->getContainer()->getRequest()->get('questionId');
        $surveyQuestion = $surveyQuestionRepo->find($surveyQuestionId);

        if (!$surveyQuestion || !$surveyQuestion->getSurvey()) {
            // dump('alma!!!');
            return [
                'surveyQuestion' => null,
                'success' => false
            ];
        }

        /**
         * Checking if user permitted this entity
        */
        if ($surveyQuestion->getSurvey()->checkCorrectWebsite() == false) {
            $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
            $securityEventHandler->addEvent('TESTING_FOREIGN_DATA', $surveyQuestionId, 'QuestionId');
        }

        // dump($this->getContainer()->getRequest()->getAll());exit;

        /*
        [] => Array()
            (0)[SurveyCreator_questionDescription_76009] => Almakérdés
            (1)[SurveyCreator_questionInputType_76009] => select
            (2)[SurveyCreator_optionDescription_76009] => Array()
                (2)[77005] => alma
                (3)[77008] => körte
            (3)[questionId] => 76009
        */

        $questionDescription = $this->getContainer()->getRequest()->get('SurveyCreator_questionDescription_'.$surveyQuestionId);
        $surveyQuestion->setDescription($questionDescription);
        $questionInputType = $this->getContainer()->getRequest()->get('SurveyCreator_questionInputType_'.$surveyQuestionId);
        $surveyQuestion->setInputType($questionInputType);
        $questionRequired = $this->getContainer()->getRequest()->get('SurveyCreator_questionRequired_'.$surveyQuestionId);
        if ($questionRequired === 'false') {
            $questionRequired = false;
        }
        if ($questionRequired === 'true') {
            $questionRequired = true;
        }
        $surveyQuestion->setRequired($questionRequired);
        $optionDescriptionPostArray = $this->getContainer()->getRequest()->get('SurveyCreator_optionDescription_'.$surveyQuestionId);

        $surveyOptions = [];
        if ($optionDescriptionPostArray) {
            $this->wireService('SurveyPackage/repository/SurveyOptionRepository');
            $surveyOptionRepo = new SurveyOptionRepository();
            foreach ($optionDescriptionPostArray as $surveyOptionId => $optionDescription) {
                $surveyOption = $surveyOptionRepo->find($surveyOptionId);
                $surveyOption->setDescription($optionDescription);
                $surveyOption->setSurveyQuestion($surveyQuestion);
                // $surveyOption = $surveyOptionRepo->store($surveyOption);
                // dump($surveyOption);
                $surveyOptions[] = $surveyOption;
            }
        }
        $surveyQuestion->setAllSurveyOptions($surveyOptions);
        if ($surveyQuestion->getSurvey()->getRepository()->isStorable($surveyQuestion->getSurvey()->getId())) {
            $surveyQuestion->getRepository()->store($surveyQuestion);
        }

        // dump($optionDescriptionPostArray);
        // dump($this->getContainer()->getRequest()->getAll());exit;

        return [
            // 'survey' => $surveyQuestion->getSurvey(),
            'surveyQuestion' => $surveyQuestion,
            'success' => $success
        ];
    }

    /**
    * Route: [name: admin_survey_addQuestion, paramChain: /admin/survey/addQuestion]
    */
    public function adminAddQuestionAction()
    {
        /**
         * Don't run saveQuestion() here! Because we just adding a new.
        */
        $this->wireService('SurveyPackage/repository/SurveyRepository');
        $repo = new SurveyRepository();
        $surveyId = (int)$this->getContainer()->getRequest()->get('surveyId');
        $survey = $repo->find($surveyId);

        /**
         * Checking if user permitted this entity
        */
        if ($survey && $survey->checkCorrectWebsite() == false) {
            $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
            $securityEventHandler->addEvent('TESTING_FOREIGN_DATA', $surveyId, 'SurveyId');
        }

        $this->wireService('SurveyPackage/entity/SurveyQuestion');
        $surveyQuestion = new SurveyQuestion();
        $surveyQuestion->setSurvey($survey);
        $surveyQuestion = $surveyQuestion->getRepository()->store($surveyQuestion);

        $response = [
            'view' => '',
            'data' => [
                'questionId' => $surveyQuestion->getId()
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_survey_removeQuestion, paramChain: /admin/survey/removeQuestion]
    */
    public function adminRemoveQuestionAction()
    {
        $saveQuestionResult = $this->saveQuestion();
        if (!$saveQuestionResult['success']) {
            return false;
        }
        $surveyQuestion = $saveQuestionResult['surveyQuestion'];

        // $this->wireService('SurveyPackage/repository/SurveyQuestionRepository');
        // $surveyQuestionRepo = new SurveyQuestionRepository();
        // $surveyQuestionId = (int)$this->getContainer()->getRequest()->get('questionId');
        // $surveyQuestion = $surveyQuestionRepo->find($surveyQuestionId);

        // if (!$surveyQuestion || !$surveyQuestion->getSurvey()) {
        //     return null;
        // }

        /**
         * Checking if user permitted this entity
        */
        // if ($surveyQuestion->getSurvey()->checkCorrectWebsite() == false) {
        //     $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
        //     $securityEventHandler->addEvent('TESTING_FOREIGN_DATA', $surveyQuestionId, 'SurveyQuestionId');
        // }

        $surveyQuestion->getRepository()->remove($surveyQuestion->getId());

        $response = [
            'view' => '',
            'data' => [
                'success' => true
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_survey_addOption, paramChain: /admin/survey/addOption]
    */
    public function adminAddOptionAction()
    {
        // $this->wireService('SurveyPackage/repository/SurveyQuestionRepository');
        // $surveyQuestionRepo = new SurveyQuestionRepository();
        // $surveyQuestionId = (int)$this->getContainer()->getRequest()->get('questionId');
        // $surveyQuestion = $surveyQuestionRepo->find($surveyQuestionId);

        // if (!$surveyQuestion || !$surveyQuestion->getSurvey()) {
        //     return null;
        // }
        $saveQuestionResult = $this->saveQuestion();
        if (!$saveQuestionResult['success']) {
            // dump($saveQuestionResult);
            return false;
        }
        $surveyQuestion = $saveQuestionResult['surveyQuestion'];

        $this->wireService('SurveyPackage/entity/SurveyOption');
        $surveyOption = new SurveyOption();
        $surveyOption->setSurveyQuestion($surveyQuestion);
        $surveyOption->getRepository()->store($surveyOption);

        $response = [
            'view' => '',
            'data' => [
                'optionId' => $surveyOption->getId()
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_survey_removeOption, paramChain: /admin/survey/removeOption]
    */
    public function adminRemoveOptionAction()
    {


        // if (!$surveyOption || !$surveyOption->getSurveyQuestion() || !$surveyOption->getSurveyQuestion()->getSurvey()) {
        //     return null;
        // }

        /**
         * Checking if user permitted this entity
        */
        // if ($surveyOption->getSurveyQuestion()->getSurvey()->checkCorrectWebsite() == false) {
        //     $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
        //     $securityEventHandler->addEvent('TESTING_FOREIGN_DATA', $surveyOptionId, 'SurveyOptionId');
        // }
        $saveQuestionResult = $this->saveQuestion();
        if (!$saveQuestionResult['success']) {
            return false;
        }
        // $surveyQuestion = $saveQuestionResult['surveyQuestion'];

        $this->wireService('SurveyPackage/repository/SurveyOptionRepository');
        $surveyOptionRepo = new SurveyOptionRepository();
        $surveyOptionId = (int)$this->getContainer()->getRequest()->get('optionId');
        $surveyOption = $surveyOptionRepo->find($surveyOptionId);
        $surveyOptionRepo->remove($surveyOption->getId());

        $response = [
            'view' => '',
            'data' => [
                'success' => true,
                'questionId' => $surveyOption->getSurveyQuestion()->getId()
                // 'optionId' => $surveyOption->getId()
            ]
        ];

        return $this->widgetResponse($response);
    }

    // public function newSurvey($contentDepth)
    // {
    //     $this->wireService('FormPackage/service/FormBuilder');
    //     // dump($this->getContainer()->getRequest()->getAll());exit;
    //     $formBuilder = new FormBuilder();
    //     $formBuilder->setPackageName('SurveyPackage');
    //     $formBuilder->setSubject('newSurvey');
    //     // $formBuilder->setPrimaryKeyValue(null);
    //     $formBuilder->addExternalPost('id');
    //     // $formBuilder->addExternalPost('FrameworkPackage_pageEdit_file');
    //     $formBuilder->setSaveRequested(false);
    //     $formBuilder->setAutoSubmit(false);
    //     $formBuilder->setSubmitted($this->getContainer()->getRequest()->get('submitted') ? : false);
    //     $form = $formBuilder->createForm();
        
    //     $viewPath = 'framework/packages/SurveyPackage/view/widget/AdminSurveysWidget/'.($contentDepth == 'flexible' ? 'newFlexibleContent' : 'new').'.php';
    //     $response = [
    //         'view' => $this->renderWidget('newSurvey', $viewPath, [
    //             'form' => $form,
    //         ]),
    //         'data' => [
    //         ]
    //     ];
        
    //     return $this->widgetResponse($response);
    // }

    // public function editSurvey($contentDepth, $survey)
    // {
    //     $viewPath = 'framework/packages/SurveyPackage/view/widget/AdminSurveysWidget/'.($contentDepth == 'flexible' ? 'editFlexibleContent' : 'edit').'.php';
    //     $response = [
    //         'view' => $this->renderWidget('editSurvey', $viewPath, [
    //             // 'entity' => $entity,
    //         ]),
    //         'data' => [
    //         ]
    //     ];

    //     return $this->widgetResponse($response);
    // }
}
