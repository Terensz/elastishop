<?php
namespace framework\packages\LegalPackage\controller;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\WidgetController;
use framework\packages\ContentPackage\service\ContentTextService;
use framework\packages\LegalPackage\entity\VisitorConsent;
use framework\packages\LegalPackage\entity\VisitorConsentAcceptance;
use framework\packages\LegalPackage\repository\VisitorConsentRepository;
use framework\packages\MarketingPackage\service\ConsentAcceptanceRequestSubscriberService;
use framework\packages\ToolPackage\service\TextAssembler;

class CookieConsentWidgetController extends WidgetController
{
    const GENERAL_SUBSCRIBER = 'general';

    private $contentTextService;

    public function __construct()
    {
        $this->wireService('LegalPackage/service/CookieConsentService');
    }

    public function getContentTextService() : ContentTextService
    {
        if (isset($this->contentTextService)) {
            return $this->contentTextService;
        }
        $this->wireService('ContentPackage/service/ContentTextService');
        $this->contentTextService = new ContentTextService();

        return $this->contentTextService;
    }

    // public function getContentTextService_OLD($subscriber) : ContentTextService
    // {
    //     if (isset($this->contentTextService[$subscriber])) {
    //         return $this->contentTextService[$subscriber];
    //     }

    //     $this->wireService('ContentPackage/service/ContentTextService');
    //     $this->contentTextService[$subscriber] = new ContentTextService();
    //     $this->contentTextService[$subscriber]->prefabPathBase = 'framework/packages/LegalPackage/consentAcceptanceRequestSubscribers/'.$subscriber.'/view/contentTexts/';
    //     $this->contentTextService[$subscriber]->prefabPathBaseType = 'source';

    //     return $this->contentTextService[$subscriber];
    // }

    /**
    * Route: [name: widget_CookieBoxWidget, paramChain: /widget/CookieBoxWidget]
    */
    public function cookieBoxWidgetAction()
    {
        $thirdPartyCookiesAcceptances = CookieConsentService::findThirdPartyCookiesAcceptances();
        $detailedListRequest = StringHelper::mendValue(App::getContainer()->getRequest()->get('detailedListRequest'));

        $acceptedCount = 0;
        $refusedCount = 0;
        foreach ($thirdPartyCookiesAcceptances as $thirdPartyCookiesAcceptance) {
            if ($thirdPartyCookiesAcceptance->getAcceptance() == VisitorConsentAcceptance::ACCEPTANCE_ACCEPTED) {
                $acceptedCount++;
            }
            if ($thirdPartyCookiesAcceptance->getAcceptance() == VisitorConsentAcceptance::ACCEPTANCE_REFUSED) {
                $refusedCount++;
            }
        }
        // dump($thirdPartyCookiesAcceptances);exit;
        
        $viewPath = 'framework/packages/LegalPackage/view/widget/CookieBoxWidget/'.($detailedListRequest ? 'detailedList' : 'widget').'.php';
        $response = [
            'view' => $this->renderWidget('CookieBoxWidget', $viewPath, [
                'container' => $this->getContainer(),
                'detailedListRequest' => $detailedListRequest,
                'thirdPartyCookiesAcceptances' => $thirdPartyCookiesAcceptances,
                'acceptedCount' => $acceptedCount,
                'refusedCount' => $refusedCount
            ]),
            'data' => [
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: widget_CookieNoticeWidget, paramChain: /widget/CookieNoticeWidget]
    */
    public function cookieNoticeWidgetAction()
    {
        return $this->processCookieNotice(false);
    }

    /**
    * Route: [name: widget_CookieNoticeWidget_submit, paramChain: /widget/CookieNoticeWidget_submit]
    */
    public function cookieNoticeWidgetSubmitAction()
    {
        $acceptAll = StringHelper::mendValue($this->getContainer()->getRequest()->get('acceptAll'));
        $refuseAll = StringHelper::mendValue($this->getContainer()->getRequest()->get('refuseAll'));

        // dump($refuseAll);exit;
        if ($acceptAll || $refuseAll) {
            $visitorConsentRepo = new VisitorConsentRepository();
            $userAccountId = $this->getContainer()->getUser()->getUserAccount() ? $this->getContainer()->getUser()->getUserAccount()->getId() : null;
            $visitorCode = $this->getContainer()->getSession()->get('visitorCode');
            $visitorConsent = $visitorConsentRepo->findByUserOrVisitorCode($userAccountId, $visitorCode);

            $thirdPartyCookiesAcceptances = CookieConsentService::findThirdPartyCookiesAcceptances();

            App::getContainer()->wireService('MarketingPackage/service/ConsentAcceptanceRequestSubscriberService');
            $subscriberService = new ConsentAcceptanceRequestSubscriberService();
            $subscribers = $subscriberService->getSubscribers();

            foreach ($subscribers as $subscriber) {
                $acceptanceFound = false;
                foreach ($thirdPartyCookiesAcceptances as $thirdPartyCookiesAcceptance) {
                    if ($thirdPartyCookiesAcceptance->getRequestSubscriber() == $subscriber) {
                        $acceptanceFound = true;
                    }
                }
                if ($acceptanceFound == false) {
                    $visitorConsentAcceptance = new VisitorConsentAcceptance();
                    $visitorConsentAcceptance->setVisitorConsent($visitorConsent);
                    $visitorConsentAcceptance->setRequestCategory(VisitorConsentAcceptance::REQUESTED_FOR_ACCEPTING_3RD_PARTY_COOKIES);
                    $visitorConsentAcceptance->setAcceptance($refuseAll ? VisitorConsentAcceptance::ACCEPTANCE_REFUSED : VisitorConsentAcceptance::ACCEPTANCE_ACCEPTED);
                    $visitorConsentAcceptance->setRequestSubscriber($subscriber);
                    $visitorConsentAcceptance = $visitorConsentAcceptance->getRepository()->store($visitorConsentAcceptance);
                    // dump($visitorConsentAcceptance);
                }
            }

            $response = [
                'view' => false,
                'data' => [
                    'removeCookieNotice' => true,
                    'removeCookieNoticeReason' => trans('all.cookies.saved')
                ]
            ];
    
            return $this->widgetResponse($response);
        }

        // dump('alma');exit;
        $subscriber = $this->getContainer()->getRequest()->get('subscriber');
        $acceptance = $this->getContainer()->getRequest()->get('acceptance');
        $this->storeThirdPartyCookiesAcceptance($subscriber, $acceptance);

        return $this->processCookieNotice(true);
    }

    public function processCookieNotice($renderFlexibleContent = false) 
    {
        // dump('alma!!!!!');exit;
        $pageRoute = App::getContainer()->getRouting()->getPageRoute()->getName();
        // dump($pageRoute);
        $removeCookieNotice = false;
        $removeCookieNoticeReason = null;
        // dump($this->getContainer()->getUser());exit;
        // dump($this->getContainer()->getUser());
        // dump($this->getSession()->get('userId'));exit;
        if ($this->getContainer()->getUser()->getType() == $this->getContainer()->getUser()::TYPE_ADMINISTRATOR || $this->getSession()->get('maintenanceMode') || in_array($pageRoute, CookieConsentService::PAGES_NOT_NEQUIRING_COOKIE_INFO)) {
            if ($this->getSession()->get('userId') != 0  && !$this->getContainer()->getUser()->getUserAccount()) {
                $removeCookieNoticeReason = 'user.is.not.in.database';
            }
            if ($this->getSession()->get('maintenanceMode')) {
                $removeCookieNoticeReason = 'maintenance.mode.is.on';
            }
            if (in_array($pageRoute, CookieConsentService::PAGES_NOT_NEQUIRING_COOKIE_INFO)) {
                $removeCookieNoticeReason = 'page.not.requires.cookie.info';
            }
            if ($this->getContainer()->getUser()::TYPE_ADMINISTRATOR) {
                $removeCookieNoticeReason = 'user.is.an.administrator';
            }
            return $this->widgetResponse([
                'view' => null,
                'data' => [
                    'removeCookieNotice' => true,
                    'removeCookieNoticeReason' => trans($removeCookieNoticeReason)
                ]
            ]);
        }

        // if ($submit == 'true') {
        //     dump($this);exit;
        //     // $acceptedCookieNotice = new VisitorConsent();
        //     // $acceptedCookieNotice->setVisitorCode($this->getSession()->get('visitorCode'));
        //     // $acceptedCookieNotice->setUserAccount($this->getContainer()->getUser()->getUserAccount() ? $this->getContainer()->getUser()->getUserAccount()->getId() : null);
        //     // $acceptedCookieNotice->setSessionAccepted(1);
        //     // $acceptedCookieNotice->setFirstPartyAccepted(0);
        //     // $acceptedCookieNotice->setThirdPartyAccepted(0);
        //     // $acceptedCookieNotice->setCreatedAt($this->getCurrentTimestamp());
        //     // $repo->store($acceptedCookieNotice);
        // }

        App::getContainer()->wireService('MarketingPackage/service/ConsentAcceptanceRequestSubscriberService');
        $subscriberService = new ConsentAcceptanceRequestSubscriberService();
        $subscribers = $subscriberService->getSubscribers();
        // dump($subscribers);exit;
        if (count($subscribers) == 0) {
            return $this->widgetResponse([
                'view' => null,
                'data' => [
                    'removeCookieNotice' => true,
                    'removeCookieNoticeReason' => 'no.subscribers'
                ]
            ]);
        } 

        $textViews = [];
        // $icons = [];
        $generalTextView = null;
        if (!in_array(self::GENERAL_SUBSCRIBER, $subscribers)) {
            $subscribers[] = self::GENERAL_SUBSCRIBER;
        }
        foreach ($subscribers as $subscriber) {
            $this->wireService('ToolPackage/service/TextAssembler');
            $textAssembler = new TextAssembler();
            // dump($this->getContentTextService($subscriber));
            $textAssembler->setContentTextService($this->getContentTextService());
            $textAssembler->setDocumentType('entry');
            $textAssembler->setPackage('LegalPackage');
            $textAssembler->setReferenceKey($subscriber.'CookieConsentInfo');
            $textAssembler->setPlaceholdersAndValues([
                'httpDomain' => $this->getUrl()->getHttpDomain()
            ]);
            $textAssembler->create();
            $textView = $textAssembler->getView();

            if ($subscriber == self::GENERAL_SUBSCRIBER) {
                $generalTextView = $textView;
            } else {
                $textViews[$subscriber] = $textView;
            }

            // dump($textView);exit;
        }
        
        $thirdPartyCookiesAcceptances = CookieConsentService::findThirdPartyCookiesAcceptances();

        // dump($thirdPartyCookiesAcceptances);

        $subscribersFound = [];
        foreach ($subscribers as $subscriber) {
            foreach ($thirdPartyCookiesAcceptances as $thirdPartyCookiesAcceptance) {
                if ($thirdPartyCookiesAcceptance->getRequestSubscriber() == $subscriber || $subscriber == self::GENERAL_SUBSCRIBER) {
                    $subscribersFound[] = $subscriber;
                }
            }
        }

        // dump($subscribers);
        // dump($subscribersFound);exit;

        if (count($subscribersFound) < count($subscribers)) {
            // dump($subscribersFound);
            // dump($subscribers);
            $viewPath = 'framework/packages/LegalPackage/view/widget/CookieNoticeWidget/'.($renderFlexibleContent ? 'widgetFlexibleContent' : 'widget').'.php';
            $view = $this->renderWidget('CookieNoticeWidget', $viewPath, [
                'container' => $this->getContainer(),
                'textViews' => $textViews,
                'subscribersFound' => $subscribersFound,
                'generalTextView' => $generalTextView,
                'generalSubscriber' => self::GENERAL_SUBSCRIBER
            ]);
        }
        else {
            $view = false;
            $removeCookieNotice = true;
            $removeCookieNoticeReason = 'all.subscribers.handled';
        }

        // dump($subscribers);
        // dump($thirdPartyCookiesAcceptances);
        // exit;
        // dump($pageRoute);exit;
        $response = [
            'view' => $view,
            'data' => [
                'removeCookieNotice' => $removeCookieNotice,
                'removeCookieNoticeReason' => $removeCookieNoticeReason
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function storeThirdPartyCookiesAcceptance(string $subscriber, string $acceptance)
    {
        $repo = new VisitorConsentRepository();

        return $repo->storeAcceptance(
            $this->getContainer()->getUser()->getId(),
            $this->getSession()->get('visitorCode'),
            VisitorConsentAcceptance::REQUESTED_FOR_ACCEPTING_3RD_PARTY_COOKIES,
            $subscriber,
            $acceptance
        );
    }

    /**
    * Route: [name: widget_CookieConsentWidget_removeAllConsent, paramChain: /widget/CookieConsentWidget_removeAllConsent]
    */
    public function removeAllConsentAction()
    {
        App::getContainer()->wireService('MarketingPackage/service/ConsentAcceptanceRequestSubscriberService');
        $subscriberService = new ConsentAcceptanceRequestSubscriberService();
        $subscribers = $subscriberService->getSubscribers();
        $repo = new VisitorConsentRepository();

        foreach ($subscribers as $subscriber) {
            $success = $repo->removeAcceptance(
                $this->getContainer()->getUser()->getId(),
                $this->getSession()->get('visitorCode'),
                VisitorConsentAcceptance::REQUESTED_FOR_ACCEPTING_3RD_PARTY_COOKIES,
                $subscriber
            );
        }

        // dump($success);exit;

        // App::redirect('/webshop');

        $response = [
            'view' => null,
            'data' => [
                'success' => $success
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: widget_CookieConsentWidget_removeRefusedConsent, paramChain: /widget/CookieConsentWidget_removeRefusedConsent]
    */
    // public function removeRefusedConsentAction()
    // {
    //     $subscriber = $this->getRequest()->get('subscriber');
    //     $repo = new VisitorConsentRepository();
    //     $success = $repo->removeAcceptance(
    //         $this->getContainer()->getUser()->getId(),
    //         $this->getSession()->get('visitorCode'),
    //         VisitorConsentAcceptance::REQUESTED_FOR_ACCEPTING_3RD_PARTY_COOKIES,
    //         $subscriber
    //     );

    //     // dump($success);exit;

    //     // App::redirect('/webshop');

    //     $response = [
    //         'view' => null,
    //         'data' => [
    //             'success' => $success
    //         ]
    //     ];

    //     return $this->widgetResponse($response);
    // }
}
