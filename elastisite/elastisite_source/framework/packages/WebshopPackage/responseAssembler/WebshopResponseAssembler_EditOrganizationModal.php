<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\core\WidgetResponse;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\kernel\view\ViewRenderer;
use framework\packages\BasicPackage\repository\CountryRepository;
use framework\packages\BusinessPackage\repository\OrganizationRepository;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\UserPackage\entity\Address;
use framework\packages\WebshopPackage\repository\ProductRepository;
use framework\packages\WebshopPackage\service\WebshopCartService;
use framework\packages\WebshopPackage\dataProvider\ProductListDataProvider;
use framework\packages\WebshopPackage\service\WebshopRequestService;
use framework\packages\WebshopPackage\service\WebshopService;
use framework\packages\WebshopPackage\service\WebshopTemporaryAccountService;

class WebshopResponseAssembler_EditOrganizationModal extends Service
{
    const ACTION_TYPE_ADD = 'Add';
    const ACTION_TYPE_EDIT = 'Edit';

    public static function assembleResponse($processedRequestData = null, $data = [])
    {
        App::getContainer()->setService('framework/packages/BasicPackage/repository/CountryRepository');
        // App::getContainer()->setService('framework/packages/UserPackage/repository/UserAccountRepository');
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        App::getContainer()->wireService('FormPackage/service/FormBuilder');
        App::getContainer()->wireService('UserPackage/entity/Address');

        $countryRepo = new CountryRepository();

        App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
        $temporaryAccount = WebshopTemporaryAccountService::getTemporaryAccount();
        
        $success = false;
        $userAccount = App::getContainer()->getUser()->getUserAccount();
        $organizationId = null;
        $submitted = StringHelper::mendValue(App::getContainer()->getRequest()->get('submitted'));
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('WebshopPackage');
        $formBuilder->setSubject('editOrganization');
        $formBuilder->addExternalPost('submitted');
        $actionType = self::ACTION_TYPE_ADD;
        if (isset($data['id']) && $data['id']) {
            App::getContainer()->wireService('BusinessPackage/repository/OrganizationRepository');
            $organizationRepo = new OrganizationRepository();
            if (!$organizationRepo->isEditable($data['id'], $temporaryAccount->getTemporaryPerson()->getId())) {
                return self::nonEditableContent();
            }
            $actionType = self::ACTION_TYPE_EDIT;
            $formBuilder->setPrimaryKeyValue(App::getContainer()->getRequest()->get('id'));
        }
        $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();
        $form->setSubmitted($submitted);
        // dump($form);exit;
        if ($form->isValid()) {
            $organization = $form->getEntity();
            $organizationRepo = $organization->getRepository();
            $address = $organization->getAddress();
            $address = $address->getRepository()->store($address);
            $organization->setAddress($address);
            // $organization = $organizationRepo->store($organization);

            /**
             * Logged-in user
             */
            if ($userAccount->getId()) {
                
                $organization->setUserAccount($userAccount);
                $organization = $organizationRepo->store($organization);
                // dump($address);exit;
            /**
             * Guest
             */
            } else {
                $organization = $organizationRepo->store($organization);
                $temporaryAccount->getTemporaryPerson()->setOrganization($organization);
                $temporaryAccount->getTemporaryPerson()->getRepository()->store($temporaryAccount->getTemporaryPerson());
            }

            $organizationId = $organization->getId();
            $success = true;
        }

        $countryRepo = new CountryRepository();
        $viewParams = [
            'actionType' => $actionType,
            'form' => $form,
            'countries' => $countryRepo->findAllAvailable(),
            'streetSuffixes' => Address::CHOOSABLE_STREET_SUFFIXES
        ];

        $viewPath = 'framework/packages/WebshopPackage/view/Sections/Checkout/EditOrganizationModal.php';
        $view = ViewRenderer::renderWidget('WebshopResponseAssembler_EditOrganizationModal', $viewPath, $viewParams);

        return [
            'view' => $view,
            'data' => [
                'actionType' => $actionType,
                // 'modalLabel' => trans('add.new.organization'),
                'modalLabel' => trans($actionType == self::ACTION_TYPE_ADD ? 'add.new.organization' : 'edit.organization'),
                // 'toastTitle' => trans('system.message'),
                // 'toastBody' => trans('organization.saved'),
                'organizationId' => $organizationId,
                'success' => $success
            ]
        ];
    }

    public static function nonEditableContent()
    {
        $viewPath = 'framework/packages/WebshopPackage/view/Sections/Checkout/EditOrganizationModalNonEditable.php';
        $view = ViewRenderer::renderWidget('WebshopResponseAssembler_EditOrganizationModalNonEditable', $viewPath, []);

        return [
            'view' => $view,
            'data' => [
            ]
        ];
    }

}