<?php
namespace framework\packages\WebshopPackage\form;

use framework\component\parent\FormSchema;

class EditCartTriggerSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:WebshopPackage/entity/CartTrigger' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'name' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'entity:WebshopPackage/entity/Product' => array(
                    'productId' => array(
                        'property' => 'id',
                        'validatorRules' => array(
                            'required' => true
                        )
                    )
                ),
                'directionOfChange' => array(
                    'validatorRules' => array(
                        'required' => true,
                        'permittedDirectionOfChange' => true
                    )
                ),
                'effectCausingStuff' => array(
                    'validatorRules' => array(
                        'required' => true,
                        'permittedEffectCausingStuff' => true
                    )
                ),
                'effectCausingValue' => array(
                    'validatorRules' => array(
                        'requiredIfNotAutomatic' => true,
                        'mustBeNullIfAutomatic' => true,
                        'validCountryAlpha2' => true,
                        'validZipCodeMask' => true,
                        'validGrossTotalPrice' => true
                    )
                ),
                'effectOperator' => array(
                    'validatorRules' => array(
                        'requiredIfNotAutomatic' => true,
                        'permittedEffectOperator' => true,
                        'zipAndCountryEffectOperatorOnlyEqualsAndNotEquals' => true,
                        'grossTotalProceEffectOperatorOnlyLessAndMoreThan' => true,
                        'mustBeNullIfAutomatic' => true
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
                'class' => 'framework/packages/WebshopPackage/form/EditCartTriggerCustomValidator',
                'method' => 'requiredIfNotAutomatic'
            ],
            [
                'class' => 'framework/packages/WebshopPackage/form/EditCartTriggerCustomValidator',
                'method' => 'mustBeNullIfAutomatic'
            ],
            [
                'class' => 'framework/packages/WebshopPackage/form/EditCartTriggerCustomValidator',
                'method' => 'permittedDirectionOfChange'
            ],
            [
                'class' => 'framework/packages/WebshopPackage/form/EditCartTriggerCustomValidator',
                'method' => 'permittedEffectCausingStuff'
            ],
            [
                'class' => 'framework/packages/WebshopPackage/form/EditCartTriggerCustomValidator',
                'method' => 'permittedEffectOperator'
            ],
            [
                'class' => 'framework/packages/WebshopPackage/form/EditCartTriggerCustomValidator',
                'method' => 'zipAndCountryEffectOperatorOnlyEqualsAndNotEquals'
            ],
            [
                'class' => 'framework/packages/WebshopPackage/form/EditCartTriggerCustomValidator',
                'method' => 'grossTotalProceEffectOperatorOnlyLessAndMoreThan'
            ],
            [
                'class' => 'framework/packages/WebshopPackage/form/EditCartTriggerCustomValidator',
                'method' => 'validCountryAlpha2'
            ],
            [
                'class' => 'framework/packages/WebshopPackage/form/EditCartTriggerCustomValidator',
                'method' => 'validZipCodeMask'
            ],
            [
                'class' => 'framework/packages/WebshopPackage/form/EditCartTriggerCustomValidator',
                'method' => 'validGrossTotalPrice'
            ]
        ];
    }
}
