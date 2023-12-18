<?php
namespace framework\packages\SurveyPackage\form;

use framework\component\parent\FormSchema;

class EditSurveySchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return array(
            'primaryEntity:SurveyPackage/entity/Survey' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'title' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'slug' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
                'description' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
                'status' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
            )
        );
    }

    public function getCustomValidators()
    {
        return [
            // [
            //     'class' => 'framework/packages/WebshopPackage/form/EditShipmentCustomValidator',
            //     'method' => 'checkOrgHungarianZip'
            // ],
        ];
    }
}
