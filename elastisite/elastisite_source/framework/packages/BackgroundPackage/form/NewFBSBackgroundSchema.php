<?php
namespace framework\packages\BackgroundPackage\form;

use framework\component\parent\FormSchema;

class NewFBSBackgroundSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:BackgroundPackage/entity/FBSBackground' => array(
                'engine' => array(
                ),
                'theme' => array(
                    'validatorRules' => array(
                        'required' => true,
                        'minThemeLength' => true
                    )
                )
            )
        );
    }

    public function getConfig()
    {
        return array(
            'entityPath' => 'framework/packages/BackgroundPackage/entity/FBSBackground',
            'repositoryPath' => 'framework/packages/BackgroundPackage/repository/FBSBackgroundRepository'
            // 'filePath' => rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/file_based_storage/backgrounds/FBSBackground.txt'
        );
    }

    public function getSchemaConfig()
    {
        return array(
            'dataRepository' => 'FBSBackgroundRepository',
            'selectDataMethod' => 'selectFBSBackground',
            'storeDataMethod' => 'storeFBSBackground'
            // 'removeDataMethod' => 'removeUserRegistration'
        );
    }

    public function getEntityConfig()
    {
        return array(
            'FBSBackground' => array(
                'entityPath' => 'framework/packages/BackgroundPackage/entity/FBSBackground',
                'repositoryPath' => 'framework/packages/BackgroundPackage/repository/FBSBackgroundRepository'
            )
        );
    }

    public function getCustomValidators()
    {
        return [
            [
                'class' => 'framework/packages/BackgroundPackage/form/FBSBackgroundCustomValidator',
                'method' => 'minThemeLength'
            ]
        ];
    }
}
