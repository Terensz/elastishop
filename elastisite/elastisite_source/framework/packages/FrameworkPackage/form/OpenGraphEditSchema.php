<?php
namespace framework\packages\FrameworkPackage\form;

use framework\component\parent\FormSchema;

class OpenGraphEditSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:FrameworkPackage/entity/OpenGraph' => array(
                'title' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'description' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                )
                // ,
                // 'status' => array(
                //     'validatorRules' => array(
                //         'required' => false
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
