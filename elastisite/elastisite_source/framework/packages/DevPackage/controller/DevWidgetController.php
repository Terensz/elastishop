<?php
namespace framework\packages\DevPackage\controller;

use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\packages\ToolPackage\service\TextAssembler;
use framework\packages\UserPackage\repository\UserAccountRepository;

class DevWidgetController extends WidgetController
{

    /**
    * name: dev_widget, paramChain: /dev/widget
    */
    public function devWidgetAction()
    {
        // $this->wireService('UserPackage/repository/UserAccountRepository');
        // $userAccountRepo = new UserAccountRepository();
        // $userAccount = $userAccountRepo->createNewEntity();

        $this->wireService('ToolPackage/service/TextAssembler');
        $textAssembler = new TextAssembler();
        $textAssembler->setDocumentType('email');
        $textAssembler->setReferenceKey('userRegistrationSuccessful');
        $textAssembler->setPlaceholdersAndValues([
            'name' => 'Alma Janos',
            'domain' => $this->getUrl()->getHttpDomain(),
            'activationLink' => $this->getUrl()->getHttpDomain().'/regisztracio/aktivalas/almakod',
        ]);
        $textAssembler->create();
        $textView = $textAssembler->getView();

        $viewPath = 'framework/packages/DevPackage/view/widget/DevWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminBackgroundsWidget', $viewPath, [
                'container' => $this->getContainer(),
                'textView' => $textView
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }
}
