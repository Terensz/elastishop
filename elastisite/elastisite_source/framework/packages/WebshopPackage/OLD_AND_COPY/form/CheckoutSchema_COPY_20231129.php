<?php
namespace framework\packages\WebshopPackage\form;

use framework\component\parent\FormSchema;

class CheckoutSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        $entitySpecs = array();

        $checkoutDataSpecs = array(
            'triggerCorporate' => array(
                'validatorRules' => array(
                    'requiredCheckbox' => false
                ),
                // 'default' => '1'
            ),
            'organizationName' => array(
                'property' => 'name',
                'validatorRules' => array(
                    'required' => false,
                    'requiredIfCorporateTriggered' => true
                )
            ),
            'taxId' => array(
                'validatorRules' => array(
                    'required' => false,
                    'requiredIfCorporateTriggered' => true,
                    'checkHungarianOrgTaxid' => true,
                )
            ),
            'orgCountry' => array(
                'property' => 'id',
                'validatorRules' => array(
                    'required' => false,
                    'requiredIfCorporateTriggered' => true
                )
            ),
            'orgZipCode' => array(
                'property' => 'zipCode',
                'validatorRules' => array(
                    'required' => false,
                    'requiredIfCorporateTriggered' => true,
                    'checkOrgHungarianZip' => true
                )
            ),
            'orgCity' => array(
                'property' => 'city',
                'validatorRules' => array(
                    'required' => false,
                    'requiredIfCorporateTriggered' => true
                )
            ),
            'orgStreet' => array(
                'property' => 'street',
                'validatorRules' => array(
                    'required' => false,
                    'requiredIfCorporateTriggered' => true
                )
            ),
            'orgStreetSuffix' => array(
                'property' => 'streetSuffix',
                'validatorRules' => array(
                    'required' => false,
                    'requiredIfCorporateTriggered' => true
                )
            ),
            'orgHouseNumber' => array(
                'property' => 'houseNumber',
                'validatorRules' => array(
                    'required' => false,
                    'requiredIfCorporateTriggered' => true
                )
            ),
            'paymentMethod' => array(
                'validatorRules' => array(
                    'required' => false
                )
            ),
            'recipient' => array(
                'validatorRules' => array(
                    'required' => true
                )
            ),
            'notice' => array(
                'validatorRules' => array(
                    'required' => false
                )
            ),
            'agreement' => array(
                'validatorRules' => array(
                    'requiredCheckbox' => true
                )
            ),
            'email' => array(
                'validatorRules' => array(
                    'required' => true,
                    'validateEmail' => true
                )
            ),
            'mobile' => array(
                'validatorRules' => array(
                    'required' => true,
                )
            )
        );

        // if (!$this->getSession()->userLoggedIn()) {
        //     $additionalCheckoutDataSpecs = array(
        //         'email' => array(
        //             'validatorRules' => array(
        //                 'required' => true,
        //                 'validateEmail' => true
        //             )
        //         ),
        //         'mobile' => array(
        //             'validatorRules' => array(
        //                 'required' => true,
        //             )
        //         )
        //     );
        //     // dump($entitySpecs);exit;
        //     $checkoutDataSpecs = array_merge($checkoutDataSpecs, $additionalCheckoutDataSpecs);
        // }

        $entitySpecs['primaryEntity:WebshopPackage/entity/CheckoutData'] = $checkoutDataSpecs;
        // dump($entitySpecs);exit;
        return $entitySpecs;
    }

    public function getCustomValidators()
    {
        return [
            [
                'class' => 'framework/packages/WebshopPackage/form/WebshopCheckoutCustomValidator',
                'method' => 'checkHungarianOrgTaxid'
            ],
            [
                'class' => 'framework/packages/WebshopPackage/form/WebshopCheckoutCustomValidator',
                'method' => 'checkOrgHungarianZip'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'minNameLength'
            ],
            [
                'class' => 'framework/packages/WebshopPackage/form/WebshopCustomValidator',
                'method' => 'requiredIfCorporateTriggered'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'validateEmail'
            ],
        ];
    }
}
