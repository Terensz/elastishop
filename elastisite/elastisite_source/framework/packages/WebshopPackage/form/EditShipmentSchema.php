<?php
namespace framework\packages\WebshopPackage\form;

use framework\component\parent\FormSchema;

class EditShipmentSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:WebshopPackage/entity/Shipment' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'code' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'adminNote' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
                // 'zipCode' => array(
                //     'validatorRules' => array(
                //         'required' => false
                //     )
                // ),
                // 'city' => array(
                //     'validatorRules' => array(
                //         'required' => false
                //     )
                // ),
                'status' => array(
                    'type' => 'select',
                    'multiple' => false
                ),
                'entity:UserPackage/entity/TemporaryAccount' => array(
                    'entity:UserPackage/entity/TemporaryPerson' => array(
                        'temporaryPersonName' => array(
                            'property' => 'name',
                            'validatorRules' => array(
                                'required' => false
                            )
                        ),
                        'entity:BusinessPackage/entity/Organization' => array(
                            'orgName' => array(
                                'property' => 'name',
                                'validatorRules' => array(
                                    'required' => false,
                                    'requiredIfOrgExists' => true
                                )
                            ),
                            'orgTaxId' => array(
                                'property' => 'taxId',
                                'validatorRules' => array(
                                    'required' => false,
                                    'requiredIfOrgExists' => true,
                                    'checkHungarianOrgTaxid' => true,
                                )
                            ),
                            'entity:UserPackage/entity/Address' => array(
                                'entity:BasicPackage/entity/Country' => array(
                                    'orgCountryId' => array(
                                        'property' => 'id',
                                        // 'technical' => true,
                                        'primaryKey' => true
                                    ),
                                ),
                                'orgZipCode' => array(
                                    'property' => 'zipCode',
                                    'validatorRules' => array(
                                        'required' => false,
                                        'requiredIfOrgExists' => true,
                                        'checkOrgHungarianZip' => true
                                    )
                                ),
                                'orgCity' => array(
                                    'property' => 'city',
                                    'validatorRules' => array(
                                        'required' => false,
                                        'requiredIfOrgExists' => true
                                    )
                                ),
                                'orgStreet' => array(
                                    'property' => 'street',
                                    'validatorRules' => array(
                                        'required' => false,
                                        'requiredIfOrgExists' => true
                                    )
                                ),
                                'orgStreetSuffix' => array(
                                    'property' => 'streetSuffix',
                                    'validatorRules' => array(
                                        'required' => false,
                                        'requiredIfOrgExists' => true
                                    )
                                ),
                                'orgHouseNumber' => array(
                                    'property' => 'houseNumber',
                                    'validatorRules' => array(
                                        'required' => false,
                                        'requiredIfOrgExists' => true
                                    )
                                )
                            )
                        ),
                        'temporaryPersonEmail' => array(
                            'property' => 'email',
                            'validatorRules' => array(
                                'required' => false
                            )
                        ),
                        'temporaryPersonMobile' => array(
                            'property' => 'mobile',
                            'validatorRules' => array(
                                'required' => true
                            )
                        ),
                        'entity:UserPackage/entity/Address' => array(
                            'addressId' => array(
                                'property' => 'id',
                                'technical' => true,
                                // 'primaryKey' => true
                            ),
                            'entity:BasicPackage/entity/Country' => array(
                                'countryId' => array(
                                    'property' => 'id',
                                    // 'technical' => true,
                                    'primaryKey' => true
                                ),
                            ),
                            'addressZipCode' => array(
                                'property' => 'zipCode',
                                'validatorRules' => array(
                                    'required' => true
                                )
                            ),
                            'addressCity' => array(
                                'property' => 'city',
                                'validatorRules' => array(
                                    'required' => true
                                )
                            ),
                            'addressStreet' => array(
                                'property' => 'street',
                                'validatorRules' => array(
                                    'required' => true
                                )
                            ),
                            'addressStreetSuffix' => array(
                                'property' => 'streetSuffix',
                                'validatorRules' => array(
                                    'required' => true
                                )
                            ),
                            'addressHouseNumber' => array(
                                'property' => 'houseNumber',
                                'validatorRules' => array(
                                    'required' => true
                                )
                            ),
                            'addressStaircase' => array(
                                'property' => 'staircase',
                                'validatorRules' => array(
                                    'required' => false
                                )
                            ),
                            'addressFloor' => array(
                                'property' => 'floor',
                                'validatorRules' => array(
                                    'required' => false
                                )
                            ),
                            'addressDoor' => array(
                                'property' => 'door',
                                'validatorRules' => array(
                                    'required' => false
                                )
                            )
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
                'class' => 'framework/packages/WebshopPackage/form/EditShipmentCustomValidator',
                'method' => 'checkOrgHungarianZip'
            ],
            [
                'class' => 'framework/packages/WebshopPackage/form/EditShipmentCustomValidator',
                'method' => 'requiredIfOrgExists'
            ],
            [
                'class' => 'framework/packages/WebshopPackage/form/EditShipmentCustomValidator',
                'method' => 'checkHungarianOrgTaxid'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'minNameLength'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'minPasswordLength'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'mixed'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'compareRetypedPassword'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'validateEmail'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'usernameFormat'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'checkUsernameAvailability'
            ]
        ];
    }
}
