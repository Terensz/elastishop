<?php
namespace framework\packages\DevPackage\form;

use framework\component\parent\FormSchema;

class DevSchema extends FormSchema
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
                    'type' => 'select'
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
                    'entity:UserPackage/entity/Address' => array(
                        'postalAddress' => array(
                            'validatorRules' => array(
                                'required' => true
                            )
                        )
                    )
                )
            )
        );
    }

    // public function getSchemaConfig()
    // {
    //     return array(
    //         'dataRepository' => 'UserAccountRepository',
    //         'selectDataMethod' => 'find',
    //         'storeDataMethod' => 'storeUserAccount'
    //         // 'removeDataMethod' => 'removeUserRegistration'
    //     );
    // }

    // public function getEntityConfig()
    // {
    //     return array(
    //         'UserAccount' => array(
    //             // 'primaryKey' => 'id',
    //             'entityPath' => 'framework/packages/UserPackage/entity/UserAccount',
    //             'repositoryPath' => 'framework/packages/UserPackage/repository/UserAccountRepository'
    //         ),
    //         'Person' => array(
    //             'entityPath' => 'framework/packages/UserPackage/entity/Person',
    //             'repositoryPath' => 'framework/packages/UserPackage/repository/PersonRepository'
    //         )
    //     );
    // }

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
