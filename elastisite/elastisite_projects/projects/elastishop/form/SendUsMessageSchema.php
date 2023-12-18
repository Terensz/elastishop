<?php
namespace projects\elastishop\form;

use framework\component\parent\FormSchema;

class SendUsMessageSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:framework/packages/ToolPackage/entity/TechnicalEmail' => array(
                'senderName' => array(
                    'validatorRules' => array(
                        'required' => true,
                        // 'minNameLength' => true
                    )
                ),
                'senderEmail' => array(
                    'validatorRules' => array(
                        'required' => true,
                        'validateEmail' => true
                    )
                ),
                'subject' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'body' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
            )
        );
    }

    public function getCustomValidators()
    {
        return [
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'minNameLength'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'validateEmail'
            ]
        ];
    }
}
