<?php
namespace framework\packages\SiteBuilderPackage\form;

use framework\component\parent\FormSchema;

class EditContentEditorUnitSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:SiteBuilderPackage/entity/ContentEditorUnit' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'description' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
                'font' => array(
                    'validatorRules' => array(
                        'requiredIfDescriptionIsFilled' => true
                    )
                ),
                'fontSize' => array(
                    'validatorRules' => array(
                        'requiredIfDescriptionIsFilled' => true
                    )
                ),
                'fontColor' => array(
                    'validatorRules' => array(
                        'requiredIfDescriptionIsFilled' => true
                    )
                ),
                'textAlign' => array(
                    'validatorRules' => array(
                        'requiredIfDescriptionIsFilled' => true
                    )
                ),
                'textShadowStyle' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                )
            )
        );
    }

    public function getCustomValidators()
    {
        return [
            [
                'class' => 'framework/packages/SiteBuilderPackage/form/EditContentEditorUnitCustomValidator',
                'method' => 'requiredIfDescriptionIsFilled'
            ],
        ];
    }
}
