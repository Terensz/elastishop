<?php
namespace framework\packages\NewsletterPackage\form;

use framework\component\parent\FormSchema;

class EditNewsletterCampaignSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:NewsletterPackage/entity/NewsletterCampaign' => array(
                'entity:NewsletterPackage/entity/Newsletter' => array(
                    'newsletter' => array(
                        'property' => 'id',
                        'validatorRules' => array(
                            'required' => true
                        )
                    )
                ),
                'title' => array(
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
