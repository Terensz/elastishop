<?php
namespace framework\packages\SurveyPackage\controller;

use App;
use framework\component\parent\PageController;
use framework\packages\FinancePackage\service\InvoiceService;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\SurveyPackage\entity\Survey;
use framework\packages\SurveyPackage\service\SurveyService;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\service\WebshopService;

class SurveyController extends PageController
{
    public function basicAdminAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    public function basicAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer()
            // 'skinName' => 'Basic'
        ]);
    }

    /*
    Notice that this is a pageController!!!
    */
    public function fillSurveyAction($surveySlug)
    {
        // dump($surveySlug);exit;
        // renderPage($viewData = [], $ajaxData = [], $skeletonPath = null, $title = null)
        $slug = App::getContainer()->getUrl()->getSubRoute();
        $this->setService('SurveyPackage/entity/Survey');
        $this->setService('SurveyPackage/service/SurveyService');
        $survey = SurveyService::getSurveyBySlug($slug);
        if (!$survey || $survey->getStatus() != Survey::STATUS_ACTIVE) {
            // dump($survey);exit;
            App::redirect('/404');
        }
        
        $renderedPage = $this->renderPage([
            'container' => $this->getContainer()
            // 'skinName' => 'Basic'
        ], [], null, $survey->getTitle());

        // dump($renderedPage);exit;

        return $renderedPage;
    }
}
