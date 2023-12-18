<?php
namespace framework\packages\DevPackage\controller;

use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\packages\UserPackage\repository\UserAccountRepository;
use framework\packages\UserPackage\repository\PersonRepository;

class DevFormWidgetController extends WidgetController
{
    /**
    * name: dev_form_widget, paramChain: /dev/form/widget
    */
    public function devFormWidgetAction()
    {
        // public function createForm($packageName, $subject, $primaryKeyValue = null, $save = true, $schemaPath = null)
        $this->setService('FormPackage/service/FormBuilder');
        $formBuilder = $this->getService('FormBuilder');
        $formBuilder->setPackageName('almaForm');
        $formBuilder->setSubject('edit');
        $formBuilder->setSchemaPath('DevPackage/form/DevSchema');
        // $formBuilder->setPrimaryKeyValue(2);
        // $formBuilder->addExternalPost('userAccountId');
        $form = $this->getService('FormBuilder')->createForm();
        // dump($form->getValueCollector());exit;
        // dump($form);exit;
        if ($form->isSubmitted()) {
            // dump($form);exit;
        }

        $viewPath = 'framework/packages/DevPackage/view/widget/DevFormWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('DevFormWidget', $viewPath, [
                'form' => $form,
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }
}
