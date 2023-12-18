<?php
namespace framework\packages\FrameworkPackage\form;

use framework\component\parent\FormSchema;

class CustomPageOpenGraphEditSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:FrameworkPackage/entity/CustomPageOpenGraph' => array(
                // 'entity:FrameworkPackage/entity/CustomPage' => array(
                //     'customPageId' => array(
                //         'property' => 'id',
                //         'validatorRules' => array(
                //             'required' => true
                //         )
                //     )
                // ),
                'entity:FrameworkPackage/entity/OpenGraph' => array(
                    'openGraphId' => array(
                        'property' => 'id',
                        'validatorRules' => array(
                            'required' => true
                        )
                    )
                ),
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
