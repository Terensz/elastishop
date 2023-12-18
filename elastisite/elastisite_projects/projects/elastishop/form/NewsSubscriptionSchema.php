<?php
namespace projects\elastishop\form;

use framework\component\parent\FormSchema;

class NewsSubscriptionSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:projects/ElastiShop/entity/NewsSubscription' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'visitorCode' => array(
                    'technical' => true,
                    'default' => $this->getContainer()->getSession()->get('visitorCode')
                ),
                'status' => array(
                    'technical' => true,
                    'default' => '1'
                ),
                'createdAt' => array(
                    'technical' => true,
                    'default' => $this->getCurrentTimestamp()
                ),
                'entity:UserPackage/entity/Person' => array(
                    'name' => array(
                        'property' => 'fullName',
                        'validatorRules' => array(
                            'required' => true,
                            'minNameLength' => true
                        )
                    ),
                    'email' => array(
                        'validatorRules' => array(
                            'required' => true,
                            // 'uniqueEmail'   => true,
                            'validateEmail' => true
                        )
                    ),
                    'mobile' => array(
                        'validatorRules' => array(
                            'required' => false
                        )
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
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'uniqueEmail'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'validateEmail'
            ]
        ];
    }
}
