<?php
namespace projects\elastishop\form;

use framework\component\parent\FormSchema;

class CustomUserRegistrationSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:UserPackage/entity/UserAccount' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'status' => array(
                    'technical' => true,
                    'default' => '0'
                ),
                'registeredAt' => array(
                    'technical' => true,
                    'default' => $this->getCurrentTimestamp()
                ),
                'entity:NewsletterPackage/entity/NewsletterSubscription' => array(
                    'subscribed' => array(
                        // 'mapped' => false,
                        'validatorRules' => array(
                            'required' => true
                        )
                    )
                ),
                'entity:UserPackage/entity/Person' => array(
                    'name' => array(
                        'property' => 'fullName',
                        'validatorRules' => array(
                            'required' => true,
                            'minNameLength' => true
                        )
                    ),
                    'username' => array(
                        'validatorRules' => array(
                            'required' => true,
                            'usernameFormat' => true,
                            'checkUsernameAvailability' => true
                        )
                    ),
                    'password' => array(
                        'type' => 'password',
                        'validatorRules' => array(
                            'required' => true,
                            'passwordIsNotTooStrong' => true,
                            'minPasswordLength' => true,
                            'mixed' => true
                        )
                    ),
                    'retypedPassword' => array(
                        'mapped' => false,
                        'type' => 'password',
                        'validatorRules' => array(
                            'required' => true,
                            'compareRetypedPassword' => true
                        )
                    ),
                    'email' => array(
                        'validatorRules' => array(
                            'required' => true,
                            'uniqueEmail'   => true,
                            'validateEmail' => true
                        )
                    ),
                    'mobile' => array(
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
            )
        );
    }

    public function getCustomValidators()
    {
        return [
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'checkHungarianZip'
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
                'method' => 'passwordIsNotTooStrong'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'mixed'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'uniqueEmail'
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
