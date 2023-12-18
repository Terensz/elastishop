<?php
namespace framework\packages\ContentPackage\form;

use framework\component\parent\FormSchema;

class ContentTextEditSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:framework/packages/ContentPackage/entity/ContentText' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'phrase' => array(
                    'validatorRules' => array(
                        'required' => false
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
        ];
    }
}
