<?php
namespace framework\packages\UserPackage\form;

use framework\component\parent\FormSchema;

class ChangePasswordSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:UserPackage/entity/UserAccount' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'entity:UserPackage/entity/Person' => array(
                    'password' => array(
                        'type' => 'password',
                        'validatorRules' => array(
                            'required' => true,
                            'minPasswordLength' => true,
                            'mixed' => true
                        )
                    ),
                    'retypedPassword' => array(
                        'technical' => true,
                        'type' => 'password',
                        'validatorRules' => array(
                            'required' => true,
                            'compareRetypedPassword' => true
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
                'method' => 'minPasswordLength'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'mixed'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'compareRetypedPassword'
            ]
        ];
    }
}
