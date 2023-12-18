<?php
namespace framework\packages\WebshopPackage\form;

use framework\component\parent\FormSchema;

class EditProductSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:WebshopPackage/entity/Product' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'name' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'nameEn' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
                'info' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'infoEn' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
                'description' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
                'descriptionEn' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
                'code' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
                // 'website' => array(
                //     'technical' => true,
                //     'default' => App::getWebsite()
                // ),
                'entity:WebshopPackage/entity/ProductCategory' => array(
                    'productCategoryId' => array(
                        'property' => 'id',
                        'validatorRules' => array(
                            'required' => false
                        )
                    )
                ),
                'specialPurpose' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
                'status' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                )
            )
        );
    }

    // public function getSchemaConfig()
    // {
    //     return array(
    //         'dataRepository' => 'ProductCategoryRepository'
    //         // 'selectDataMethod' => 'find',
    //         // 'storeDataMethod' => 'store'
    //         // 'removeDataMethod' => 'removeUserRegistration'
    //     );
    // }

    // public function getEntityConfig()
    // {
    //     return array(
    //         'ProductCategory' => array(
    //             // 'primaryKey' => 'id',
    //             'entityPath' => 'framework/packages/WebshopPackage/entity/ProductCategory',
    //             'repositoryPath' => 'framework/packages/WebshopPackage/repository/ProductCategoryRepository'
    //         )
    //     );
    // }

    public function getCustomValidators()
    {
        return [
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
