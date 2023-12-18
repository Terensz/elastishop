<?php
namespace projects\ASC\form;

use framework\component\parent\FormSchema;

class CustomUserRegistrationSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:projects/ASC/entity/ProjectUser' => array(
                'primaryLanguageCode' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'entity:UserPackage/entity/UserAccount' => array(
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
