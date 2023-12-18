<?php
namespace framework\packages\WebshopPackage\form;

use framework\component\parent\FormSchema;

class EditOrganizationSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:BusinessPackage/entity/Organization' => array(
                'name' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'taxId' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'entity:UserPackage/entity/Address' => array(
                    'entity:BasicPackage/entity/Country' => array(
                        'country' => array(
                            'property' => 'id',
                            'validatorRules' => array(
                                'required' => true
                            )
                        )
                    ),
                    'zipCode' => array(
                        'validatorRules' => array(
                            'required' => true,
                            'checkHungarianZip' => true
                        )
                    ),
                    'city' => array(
                        'validatorRules' => array(
                            'required' => true
                        )
                    ),
                    'street' => array(
                        'validatorRules' => array(
                            'required' => true
                        )
                    ),
                    'streetSuffix' => array(
                        'validatorRules' => array(
                            'required' => true
                        )
                    ),
                    'houseNumber' => array(
                        'validatorRules' => array(
                            'required' => true
                        )
                    ),
                    'staircase' => array(
                        'validatorRules' => array(
                            'required' => false
                        )
                    ),
                    'floor' => array(
                        'validatorRules' => array(
                            'required' => false
                        )
                    ),
                    'door' => array(
                        'validatorRules' => array(
                            'required' => false
                        )
                    )
                )
            )
        );
    }

    public function getCustomValidators()
    {
        return [
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'checkHungarianZip'
            ]
        ];
    }
}
