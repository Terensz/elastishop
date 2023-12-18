<?php
namespace framework\packages\FrameworkPackage\form;

use framework\component\parent\FormSchema;

class CustomPageKeywordsEditSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:SeoPackage/entity/PageKeyword' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                // 'titleReference' => array(
                //     'validatorRules' => array(
                //         'required' => true
                //     )
                // )
            )
        );
    }

    public function getCustomValidators()
    {
        return [
            // [
            //     'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
            //     'method' => 'checkHungarianZip'
            // ]
        ];
    }
}
