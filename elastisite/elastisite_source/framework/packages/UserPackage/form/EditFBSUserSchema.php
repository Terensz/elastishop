<?php
namespace framework\packages\UserPackage\form;

use framework\component\parent\FormSchema;

class EditFBSUserSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:UserPackage/entity/FBSUser' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'name' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'username' => array(
                    'validatorRules' => array(
                        'required' => true,
                        'checkUsernameAvailability' => true
                    )
                ),
                'displayedPassword' => array(
                    'type' => 'password'
                ),
                'email' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'highestPermissionGroup' => array(
                    'validatorRules' => array(
                        'required' => false
                    ),
                    'type' => 'select',
                    'multiple' => true
                ),
                'status' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                )
            )
        );
    }

    // public function getConfig()
    // {
    //     return array(
    //         'entityPath' => 'framework/packages/UserPackage/entity/FBSUser',
    //         'repositoryPath' => 'framework/packages/UserPackage/repository/FBSUserRepository',
    //         'filePath' => rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/file_based_storage/users/FBSUsers.txt'
    //     );
    // }

    // public function getSchemaConfig()
    // {
    //     return array(
    //         'dataRepository' => 'FBSUserRepository',
    //         'selectDataMethod' => 'selectFBSUser',
    //         'storeDataMethod' => 'storeFBSUser'
    //         // 'removeDataMethod' => 'removeUserRegistration'
    //     );
    // }

    // public function getEntityConfig()
    // {
    //     return array(
    //         'FBSUser' => array(
    //             'entityPath' => 'framework/packages/UserPackage/entity/FBSUser',
    //             'repositoryPath' => 'framework/packages/UserPackage/repository/FBSUserRepository'
    //         )
    //     );
    // }

    public function getCustomValidators()
    {
        return [
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
                'method' => 'checkUsernameAvailability'
            ]
        ];
    }
}
