<?php
namespace framework\packages\SiteBuilderPackage\form;

use framework\component\parent\FormSchema;

class EditBuiltPageSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:SiteBuilderPackage/entity/BuiltPage' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'routeName' => array(
                    'validatorRules' => array(
                        'required' => true,
                        'slugizedRouteName' => true,
                        'editableRouteName' => true,
                        'uniqueRouteName' => true
                    )
                ),
                'title' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'structure' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
                // 'numberOfPanels' => array(
                //     'validatorRules' => array(
                //         'required' => false
                //     )
                // ),
                // 'isMenuItem' => array(
                //     'validatorRules' => array(
                //         'required' => false
                //     )
                // ),
                'permission' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
                'status' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
            )
        );
    }

    public function getCustomValidators()
    {
        return [
            [
                'class' => 'framework/packages/SiteBuilderPackage/form/EditBuiltPageCustomValidator',
                'method' => 'slugizedRouteName'
            ],
            [
                'class' => 'framework/packages/SiteBuilderPackage/form/EditBuiltPageCustomValidator',
                'method' => 'editableRouteName'
            ],
            [
                'class' => 'framework/packages/SiteBuilderPackage/form/EditBuiltPageCustomValidator',
                'method' => 'uniqueRouteName'
            ],
        ];
    }
}
