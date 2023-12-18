<?php
namespace projects\elastishop\form;

use framework\component\parent\FormSchema;

class SurveyOfUserSatisfactionSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:projects/ElastiShop/entity/SurveyOfUserSatisfaction' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                // 'visitorCode' => array(
                //     'default' => $this->getSession()->get('visitorCode')
                // ),
                'answer1' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'answer2' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'answer3' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'answer4' => array(
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
                'method' => 'minNameLength'
            ]
        ];
    }
}
