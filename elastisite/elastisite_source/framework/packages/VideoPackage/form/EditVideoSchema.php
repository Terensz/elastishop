<?php
namespace framework\packages\VideoPackage\form;

use framework\component\parent\FormSchema;

class EditVideoSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:VideoPackage/entity/Video' => array(
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
