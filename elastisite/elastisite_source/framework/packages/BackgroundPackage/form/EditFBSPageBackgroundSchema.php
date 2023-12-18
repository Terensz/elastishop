<?php
namespace framework\packages\BackgroundPackage\form;

use framework\component\parent\FormSchema;

class EditFBSPageBackgroundSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:BackgroundPackage/entity/FBSPageBackground' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'routeName' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'fbsBackgroundTheme' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
                'backgroundColor' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
            )
        );
    }

    public function getConfig()
    {
        return array(
            'entityPath' => 'framework/packages/BackgroundPackage/entity/FBSPageBackground',
            'repositoryPath' => 'framework/packages/BackgroundPackage/repository/FBSPageBackgroundRepository',
            'filePath' => rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/file_based_storage/backgrounds/FBSPageBackground.txt'
        );
    }

    public function getSchemaConfig()
    {
        return array(
            'dataRepository' => 'FBSPageBackgroundRepository',
            'selectDataMethod' => 'selectFBSPageBackground',
            'storeDataMethod' => 'storeFBSPageBackground'
            // 'removeDataMethod' => 'removeUserRegistration'
        );
    }

    public function getEntityConfig()
    {
        return array(
            'FBSPageBackground' => array(
                'entityPath' => 'framework/packages/BackgroundPackage/entity/FBSPageBackground',
                'repositoryPath' => 'framework/packages/BackgroundPackage/repository/FBSPageBackgroundRepository'
            )
        );
    }

    public function getCustomValidators()
    {
        // return [
        //     [
        //         'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
        //         'method' => 'compareRetypedPassword'
        //     ],
        //     [
        //         'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
        //         'method' => 'validateEmail'
        //     ]
        // ];
    }
}
