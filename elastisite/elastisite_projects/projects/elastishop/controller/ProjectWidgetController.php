<?php
namespace projects\elastishop\controller;

use framework\component\parent\WidgetController;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\ToolPackage\service\Mailer;

class ProjectWidgetController extends WidgetController
{
    public function getLoginMessage()
    {
        $messages = $this->getSystemMessages(['subject' => 'login']);
        $message = (isset($messages[0])) ? $messages[0] : null;
        if (!$message && $this->getContainer()->isAjax() && $this->getRequest()->get('LoginWidget_username') === '') {
            $this->addSystemMessage('login.missing.username', 'error', 'login');
            return $this->getLoginMessage();
        } else {
            return $message;
        }
    }

    public function contentWidgetLoader()
    {
        $viewPath = 'framework/packages/ElastiSitePackage/view/widget/ESMenuWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('ESMenuWidget', $viewPath, [
                // 'container' => $this->getContainer(),
                'documentTitle' => '',
                'message' => ''
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: elastisite_ESMenuWidget, paramChain: /elastisite/ESMenuWidget]
    */
    // public function beekeeperStoryWidgetAction()
    // {
    //     $viewPath = 'framework/packages/ElastiSitePackage/view/widget/BeekeeperStoryWidget/widget.php';

    //     $response = [
    //         'view' => $this->renderWidget('BeekeeperStoryWidget', $viewPath, [
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
    * Route: [name: elastisite_ESMenuWidget, paramChain: /elastisite/ESMenuWidget]
    */
    public function eSMenuWidgetAction()
    {
        $viewPath = 'framework/packages/ElastiSitePackage/view/widget/ESMenuWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('ESMenuWidget', $viewPath, [
                // 'container' => $this->getContainer(),
                'documentTitle' => '',
                'message' => ''
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: elastisite_ESTitleWidget, paramChain: /elastisite/ESTitleWidget]
    */
    public function eSTitleWidgetAction()
    {
        $viewPath = 'framework/packages/ElastiSitePackage/view/widget/ESTitleWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('ESTitleWidget', $viewPath, [
                // 'container' => $this->getContainer(),
                'documentTitle' => '',
                'message' => ''
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: elastisite_ESContentWidget, paramChain: /elastisite/ESContentWidget]
    */
    public function eSContentWidgetAction()
    {
        $viewPath = 'framework/packages/ElastiSitePackage/view/widget/ESContentWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('ESContentWidget', $viewPath, [
                // 'container' => $this->getContainer(),
                'file' => $this->getContainer()->getRouting()->getPageRoute()->getName().'_'.$this->getSession()->getLocale(),
                'documentTitle' => '',
                'message' => ''
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponseWithWordExplanation($response);
    }

    /**
    * Route: [name: SideSubmenuWidget, paramChain: /SideSubmenuWidget]
    */
    // public function sideSubmenuWidgetAction()
    // {
    //     // dump(self::MENU_SYSTEM);exit;
    //     $viewPath = 'projects/ElastiShop/view/widget/SideSubmenuWidget/widget.php';
    //     $view = $this->renderWidget('SideSubmenuWidget', $viewPath, [
    //         'container' => $this->getContainer(),
    //         // 'file' => $this->getContainer()->getRouting()->getPageRoute()->getName().'_'.$this->getSession()->getLocale(),
    //         'title' => trans($this->getContainer()->getRouting()->getPageRoute()->getTitle()),
    //         'routeName' => $this->getContainer()->getRouting()->getPageRoute()->getName(),
    //         // 'menuSystem' => self::MENU_SYSTEM
    //     ]);
    //     $response = [
    //         'view' => $view,
    //         'data' => []
    //     ];

    //     // dump($response);exit;

    //     return $this->widgetResponse($response);
    // }

    /**
    * Route: [name: widget_ContactWidget, paramChain: /widget/ContactWidget]
    */
    public function contactWidgetAction()
    {
        // $this->wireService('projects/ElastiShop/repository/SurveyOfUserSatisfactionRepository');
        // $repo = new SurveyOfUserSatisfactionRepository();

        // $this->getSession()->set('newsSubscriptionFilled', null);
        // $this->getSession()->set('surveyOfUserSatisfactionFilled', null);
        
        // dump($repo->findAll());exit;

        // $formBuilder2 = new FormBuilder();
        // $formBuilder2->setPackageName('ElastiSite');
        // $formBuilder2->setSubject('newsSubscription');
        // $formBuilder1->setSchemaPath('projects/ElastiShop/form/NewsSubscriptionSchema.php');
        // $formBuilder2->addExternalPost('id');
        // $formBuilder2->setSaveRequested(false);
        // $form2 = $formBuilder2->createForm();

        // dump('alma');

        $viewPath = 'projects/ElastiShop/view/widget/ContactWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('ContactWidget', $viewPath, [
                // 'newsSubscriptionForm' => $this->getNewsSubscriptionForm(),
                'sendUsMessageForm' => $this->getSendUsMessageForm(),
                'sendUsMessageMailSent' => false,
                'newsSubscriptionFilled' => $this->getSession()->get('newsSubscriptionFilled')
                // 'surveyOfUserSatisfactionFilled' => $this->getSession()->get('surveyOfUserSatisfactionFilled'),
                // 'surveyRes' => $repo->findAll()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    public function getNewsSubscriptionForm()
    {
        $this->wireService('FormPackage/service/FormBuilder');
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('ElastiSite');
        $formBuilder->setSubject('newsSubscription');
        $formBuilder->setSchemaPath('projects/ElastiShop/form/NewsSubscriptionSchema');
        $formBuilder->addExternalPost('id');
        $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();
        
        return $form;
    }

    /**
    * Route: [name: widget_NewsSubscriptionForm, paramChain: /widget/NewsSubscriptionForm]
    */
    // public function newsSubscriptionFormAction()
    // {
    //     $form = $this->getNewsSubscriptionForm();
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $newsSub = $form->getEntity();
    //         $person = $newsSub->getPerson();
    //         $person->setUserAccount(null);
    //         $person = $person->getRepository()->store($person);
    //         $newsSub->setPerson($person);
    //         $newsSub = $newsSub->getRepository()->store($newsSub);

    //         $this->getSession()->set('newsSubscriptionFilled', true);

    //         $viewPath = 'projects/ElastiShop/view/widget/ContactWidget/newsSubscriptionSaved.php';
    //     } else {
    //         $viewPath = 'projects/ElastiShop/view/widget/ContactWidget/newsSubscription.php';
    //     }

    //     $response = [
    //         'view' => $this->renderWidget('newsSubscription', $viewPath, [
    //             // 'container' => $this->getContainer(),
    //             'newsSubscriptionForm' => $form
    //         ]),
    //         'data' => [
    //             'date' => date('Y-m-d H:i:s'),
    //             'submitted' => $form->isSubmitted(),
    //             'formIsValid' => $form->isValid()
    //         ],
    //     ];

    //     return $this->widgetResponse($response);
    // }

    public function getSurveyOfUserSatisfactionForm()
    {
        $this->wireService('FormPackage/service/FormBuilder');
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('ElastiSite');
        $formBuilder->setSubject('surveyOfUserSatisfaction');
        $formBuilder->setSchemaPath('projects/ElastiShop/form/SurveyOfUserSatisfactionSchema');
        $formBuilder->addExternalPost('id');
        // $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();

        return $form;
    }

    /**
    * Route: [name: surveyOfUserSatisfactionForm, paramChain: /surveyOfUserSatisfactionForm]
    */
    // public function surveyOfUserSatisfactionFormAction()
    // {
    //     $form = $this->getSurveyOfUserSatisfactionForm();
    //     if ($form->isSubmitted() && $form->isValid() && $form->getSaved()) {

    //         $this->getSession()->set('surveyOfUserSatisfactionFilled', true);

    //         $viewPath = 'projects/ElastiShop/view/widget/ContactWidget/surveyOfUserSatisfactionSaved.php';
    //     } else {
    //         $viewPath = 'projects/ElastiShop/view/widget/ContactWidget/surveyOfUserSatisfaction.php';
    //     }

    //     $response = [
    //         'view' => $this->renderWidget('surveyOfUserSatisfaction', $viewPath, [
    //             // 'container' => $this->getContainer(),
    //             'surveyOfUserSatisfactionForm' => $form
    //         ]),
    //         'data' => []
    //     ];

    //     return $this->widgetResponse($response);
    // }

    public function getSendUsMessageForm()
    {
        $this->wireService('FormPackage/service/FormBuilder');
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('ElastiSite');
        $formBuilder->setSubject('sendUsMessage');
        $formBuilder->setSchemaPath('projects/ElastiShop/form/SendUsMessageSchema');
        // $formBuilder->addExternalPost('id');
        $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();

        return $form;
    }

    /**
    * Route: [name: ajax_sendUsMessageForm, paramChain: /ajax/sendUsMessageForm]
    */
    public function sendUsMessageFormAction()
    {
        $form = $this->getSendUsMessageForm();
        $mailSent = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $this->wireService('ToolPackage/service/Mailer');
            $mailer = new Mailer();
            $mailer->setSubject($this->getContainer()->getCompanyData('brand').' - '.trans('user.message'));
            $mailer->setBody('Message from '.$form->getValueCollector()->getDisplayed('senderName').' ('.$form->getValueCollector()->getDisplayed('senderEmail').'):<br>
            <div class="mail-body" style="font: 16px Arial; color: #1d2833; white-space: pre-line;">'.$form->getValueCollector()->getDisplayed('body').'</div>');
            // // $email = $userAccount->getPerson()->getEmail();
            $mailer->addRecipient($this->getContainer()->getCompanyData('email'), $this->getContainer()->getCompanyData('brand'));
            $mailer->addBCC('terencecleric@gmail.com', 'Tudjukki');
            $mailer->send();
            $mailSent = $mailer->success;
            // $mailSent = true;
            // dump($mailer); exit;
        }

        $viewPath = 'projects/ElastiShop/view/widget/ContactWidget/sendUsMessage.php';

        $response = [
            'view' => $this->renderWidget('sendUsMessage', $viewPath, [
                // 'container' => $this->getContainer(),
                'sendUsMessageForm' => $form,
                'sendUsMessageMailSent' => $mailSent
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: HomepageWidget, paramChain: /HomepageWidget]
    */
    public function homepageWidgetAction()
    {
        // dump('alma');exit;
        // $viewPath = 'framework/packages/ElastiSitePackage/view/widget/HomepageWidget/widget.php';
        $viewPath = 'projects/ElastiShop/view/widget/HomepageWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('HomepageWidget', $viewPath, [
                // 'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        return $this->widgetResponseWithWordExplanation($response);
    }

    /**
    * Route: [name: HomepageSideWidget, paramChain: /HomepageSideWidget]
    */
    public function homepageSideWidgetAction()
    {
        // dump('alma');exit;
        // $viewPath = 'framework/packages/ElastiSitePackage/view/widget/HomepageWidget/widget.php';
        $viewPath = 'projects/ElastiShop/view/widget/HomepageSideWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('HomepageSideWidget', $viewPath, [
                // 'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        return $this->widgetResponseWithWordExplanation($response);
    }

    /**
    * Route: [name: ElastiSitePromo1Widget, paramChain: /ElastiSitePromo1Widget]
    */
    public function elastiSitePromo1WidgetAction()
    {
        // $viewPath = 'framework/packages/ElastiSitePackage/view/widget/ElastiSitePromo1Widget/widget.php';
        $viewPath = 'projects/ElastiShop/view/widget/ElastiSitePromo1Widget/widget.php';

        $response = [
            'view' => $this->renderWidget('ElastiSitePromo1Widget', $viewPath, [
                // 'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        return $this->widgetResponseWithWordExplanation($response);
    }
}
