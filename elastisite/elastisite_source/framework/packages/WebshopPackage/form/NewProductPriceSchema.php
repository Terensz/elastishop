<?php
namespace framework\packages\WebshopPackage\form;

use framework\component\parent\FormSchema;

/**
 * There is no EditProductPriceSchema, and please: NEVER create it!
 * Product price is NOT modifiable, and it is so good. (According to: pricing rules no. 1.)
*/
class NewProductPriceSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:WebshopPackage/entity/ProductPrice' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                // 'entity:WebshopPackage/entity/Product' => array(
                //     'productId' => array(
                //         'property' => 'id',
                //         'validatorRules' => array(
                //             'required' => false
                //         )
                //     )
                // ),
                'title' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                // 'netPrice' => array(
                //     'validatorRules' => array(
                //         'required' => true,
                //         'isNumeric' => true,
                //         'lessThanListPrice' => true, 
                //         'grossMustBeWholeNumber' => true
                //     )
                // ),
                'grossPrice' => array(
                    'validatorRules' => array(
                        'required' => true,
                        'isNumeric' => true,
                        // 'sameCurrencyAsOtherPricesOfThisProduct' => true, 
                        'lessThanGrossListPrice' => true,
                        // 'grossMustBeWholeNumber' => true
                    )
                ),
                'priceType' => array(
                    'validatorRules' => array(
                        'required' => true,
                        'allowedPriceTypes' => true
                    )
                ),
                'vat' => array(
                    'validatorRules' => array(
                        'required' => true,
                        'isNumeric' => true,
                        'sameVatAsOtherPricesOfThisProduct' => true
                        // 'between1And100' => true,
                    )
                )
                // 'productId' => array(
                //     'technical' => true
                // ),
                // 'status' => array(
                //     'validatorRules' => array(
                //         'required' => false
                //     )
                // )
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
                'class' => 'framework/packages/WebshopPackage/form/WebshopCustomValidator',
                'method' => 'isNumeric'
            ],
            [
                'class' => 'framework/packages/WebshopPackage/form/WebshopCustomValidator',
                'method' => 'lessThanGrossListPrice'
            ],
            [
                'class' => 'framework/packages/WebshopPackage/form/WebshopCustomValidator',
                'method' => 'allowedPriceTypes'
            ],
            // [
            //     'class' => 'framework/packages/WebshopPackage/form/WebshopCustomValidator',
            //     'method' => 'grossMustBeWholeNumber'
            // ],
            [
                'class' => 'framework/packages/WebshopPackage/form/WebshopCustomValidator',
                'method' => 'sameVatAsOtherPricesOfThisProduct'
            ],
            // [
            //     'class' => 'framework/packages/WebshopPackage/form/WebshopCustomValidator',
            //     'method' => 'sameCurrencyAsOtherPricesOfThisProduct'
            // ],
            [
                'class' => 'framework/packages/WebshopPackage/form/WebshopCustomValidator',
                'method' => 'between1And100'
            ]
        ];
    }
}
