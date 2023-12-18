<?php
namespace framework\packages\SiteBuilderPackage\form;

use framework\component\parent\FormSchema;

class EditContentEditorUnitCaseSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:SiteBuilderPackage/entity/ContentEditorUnitCase' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'verticalPositioningDirection' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'verticalPosition' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'horizontalPositioningDirection' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'horizontalPosition' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'height' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
                'width' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
                'class' => array(
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
        ];
    }
}
