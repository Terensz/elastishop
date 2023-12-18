<?php
namespace framework\packages\StaffPackage\form;

use framework\component\parent\FormSchema;

class EditStaffMemberSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:StaffPackage/entity/StaffMember' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'organization' => array(
                    'validatorRules' => array(
                    )
                ),
                'division' => array(
                    'validatorRules' => array(
                    )
                ),
                'staffMemberStatus' => array(
                    'property' => 'status',
                    'type' => 'select',
                    'multiple' => false
                ),
                'trainedAt' => array(
                    'type' => 'date',
                    'multiple' => false,
                    'validatorRules' => array(
                        'dateFormat' => true
                    )
                ),
                'entity:UserPackage/entity/Person' => array(
                    'id' => array(
                        'technical' => true,
                        'primaryKey' => true
                    ),
                    'name' => array(
                        'property' => 'fullName',
                        'validatorRules' => array(
                            'required' => true,
                            'minNameLength' => true
                        )
                    ),
                    'username' => array(
                        'validatorRules' => array(
                            'required' => true,
                            'usernameFormat' => true,
                            'checkUsernameAvailability' => true
                        )
                    ),
                    'password' => array(
                        'property' => 'displayedPassword',
                        'type' => 'password',
                        'validatorRules' => array(
                            // 'required' => true,
                            'minPasswordLength' => true,
                            'mixed' => true
                        )
                    ),
                    'email' => array(
                        'validatorRules' => array(
                            // 'required' => true,
                            'validateEmail' => true
                        )
                    ),
                    'mobile' => array(
                        'validatorRules' => array(
                            // 'required' => true
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
                'class' => 'framework/packages/BasicPackage/form/BasicCustomValidator',
                'method' => 'dateFormat'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'minNameLength'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'minPasswordLength'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'mixed'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'compareRetypedPassword'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'validateEmail'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'usernameFormat'
            ],
            [
                'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
                'method' => 'checkUsernameAvailability'
            ]
        ];
    }
}
