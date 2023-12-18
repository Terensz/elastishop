<?php
namespace framework\packages\NewsletterPackage\form;

use framework\component\parent\FormSchema;

class EditNewsletterSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:NewsletterPackage/entity/Newsletter' => array(
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
                'status' => array(
                    'validatorRules' => array(
                        'required' => true
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
                'method' => 'uniqueEmail'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'validateEmail'
            ],
        ];
    }
}
