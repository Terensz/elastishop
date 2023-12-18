<?php
namespace framework\packages\NewsletterPackage\form;

use framework\component\parent\FormSchema;

class EditNewsletterDispatchProcessSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:NewsletterPackage/entity/NewsletterDispatchProcess' => array(
                'entity:NewsletterPackage/entity/NewsletterCampaign' => array(
                    'newsletterCampaign' => array(
                        'property' => 'id',
                        'validatorRules' => array(
                            'required' => true
                        )
                    )
                ),
                'mode' => array(
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
