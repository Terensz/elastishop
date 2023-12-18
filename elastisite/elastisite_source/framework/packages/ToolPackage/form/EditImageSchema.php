<?php
namespace framework\packages\ToolPackage\form;

use framework\component\parent\FormSchema;

class EditImageSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:TechnicalFile' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'title' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'active' => array(
                    'type' => 'select',
                    'multiple' => false
                )
            )
        );
    }

    public function getSchemaConfig()
    {
        return array(
            'dataRepository' => 'TechnicalFileRepository',
            'selectDataMethod' => 'find',
            'storeDataMethod' => 'store'
            // 'removeDataMethod' => 'removeUserRegistration'
        );
    }

    public function getEntityConfig()
    {
        return array(
            'TechnicalFile' => array(
                // 'primaryKey' => 'id',
                'entityPath' => 'framework/packages/ToolPackage/entity/TechnicalFile',
                'repositoryPath' => 'framework/packages/ToolPackage/repository/TechnicalFileRepository'
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
