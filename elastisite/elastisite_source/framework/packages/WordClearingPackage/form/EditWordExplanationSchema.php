<?php
namespace framework\packages\WordClearingPackage\form;

use framework\component\parent\FormSchema;

class EditWordExplanationSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:WordClearingPackage/entity/WordExplanation' => array(
                'keyText' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'explanation' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                )
                // 'createdAt' => array(
                //     'technical' => true,
                //     'default' => $this->getCurrentTimestamp()
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
