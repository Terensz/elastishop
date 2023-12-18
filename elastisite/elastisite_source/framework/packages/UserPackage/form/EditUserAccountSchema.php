<?php
namespace framework\packages\UserPackage\form;

use framework\component\parent\FormSchema;

class EditUserAccountSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:UserPackage/entity/UserAccount' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'isTester' => array(
                    'type' => 'select',
                    'multiple' => false
                ),
                'status' => array(
                    'type' => 'select',
                    'multiple' => false
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
                        'property' => 'displayedPassword',
                        'type' => 'password',
                        'validatorRules' => array(
                            'minPasswordLength' => true,
                            'mixed' => true
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
                            'required' => true
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
