<?php
namespace framework\packages\SchedulePackage\controller;

use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;

class ScheduleWidgetController extends WidgetController
{
    /**
    * Route: [name: admin_events_widget, paramChain: /admin/events/widget]
    */
    public function adminEventsWidgetAction()
    {
        dump('ALMA!!!!');exit;
        $viewPath = 'framework/packages/SchedulePackage/view/widget/AdminEventsWidget/widget.php';

        $req = $this->getContainer()->getRequest();
        $events = [];
        $response = [
            'view' => $this->renderWidget('AdminEventsWidget', $viewPath, [
                'container' => $this->getContainer(),
                'events' => $events
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: calendar_widget, paramChain: /calendar/widget]
    */
    public function calendarWidgetAction()
    {
        $viewPath = 'framework/packages/SchedulePackage/view/widget/CalendarWidget/widget.php';

        $req = $this->getContainer()->getRequest();
        
        $appointments = [];

        $actualYear = date('Y');
        $actualMonth = date('m');

        $response = [
            'view' => $this->renderWidget('CalendarWidget', $viewPath, [
                'container' => $this->getContainer(),
                'appointments' => $appointments,
                'actualYear' => $actualYear,
                'actualMonth' => $actualMonth
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_event_edit, paramChain: /admin/event/edit]
    */
    public function eventEditAction()
    {
        $this->setService('ArticlePackage/loader/Loader');
        $repo = $this->getService('ArticleRepository');
        $this->setService('FormPackage/service/FormBuilder');
        $form = $this->getService('FormBuilder')->createForm(
            'SchedulePackage',
            'eventEdit',
            $this->getContainer()->getRequest()->get('eventId')
        );

        $viewPath = 'framework/packages/SchedulePackage/view/admin/form.php';
        $response = [
            'view' => $this->renderWidget('eventEdit', $viewPath, [
                'container' => $this->getContainer(),
                'form' => $form,
                'articleId' => $this->getContainer()->getRequest()->get('articleId'),
                'hardCodedArticles' => $repo->getHardCodedArticles()
            ]),
            'data' => [
                'form' => $form,
                'request' => $this->getContainer()->getRequest()->findAll()
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: event_delete, paramChain: /event_delete]
    */
    public function eventDeleteAction()
    {
        $this->setService('ArticlePackage/loader/Loader');
        $repo = $this->getService('ArticleRepository');
        // dump($this->getContainer()->getUrl());
        $repo->removeBy(['id' => $this->getContainer()->getRequest()->get('articleId')]);

        $response = [
            'view' => ''
            ,
            'data' => [
                'articleId' => $this->getContainer()->getRequest()->get('articleId')
            ]
        ];

        return $this->widgetResponse($response);
    }
}
