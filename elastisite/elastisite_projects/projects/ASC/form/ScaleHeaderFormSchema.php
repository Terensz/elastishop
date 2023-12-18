<?php
namespace projects\ASC\form;

use App;
use framework\component\parent\FormSchema;
use projects\ASC\entity\AscScale;

class ScaleHeaderFormSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        App::getContainer()->wireService('projects/ASC/entity/AscScale');
        return array(
            'primaryEntity:projects/ASC/entity/TechnicalScaleHeader' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'situation' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'initialLanguage' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'title' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'description' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'status' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                )
                // 'createdBy' => array(
                //     'technical' => true,
                //     'default' => App::getContainer()->getUser()->getId()
                // ),
                // 'createdAt' => array(
                //     'technical' => true,
                //     'default' => $this->getCurrentTimestamp()
                // )
            )
        );
    }

    public function getCustomValidators()
    {
        return [
            // [
            //     'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
            //     'method' => 'minNameLength'
            // ],
            // [
            //     'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
            //     'method' => 'uniqueEmail'
            // ],
            // [
            //     'class' => 'framework/packages/UserPackage/form/UserRegistrationCustomValidator',
            //     'method' => 'validateEmail'
            // ]
        ];
    }
}
