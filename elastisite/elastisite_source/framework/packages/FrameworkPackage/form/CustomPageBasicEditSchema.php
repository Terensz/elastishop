<?php
namespace framework\packages\FrameworkPackage\form;

use framework\component\parent\FormSchema;

class CustomPageBasicEditSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:FrameworkPackage/entity/CustomPage' => array(
                'routeName' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                )
                // 'title' => array(
                //     'validatorRules' => array(
                //         'required' => true
                //     )
                // ),
                // 'titleEn' => array(
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
