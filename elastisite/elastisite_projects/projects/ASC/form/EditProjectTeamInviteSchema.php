<?php
namespace projects\ASC\form;

use App;
use framework\component\parent\FormSchema;
use projects\ASC\entity\AscScale;

class EditProjectTeamInviteSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        App::getContainer()->wireService('projects/ASC/entity/AscScale');
        return array(
            'primaryEntity:projects/ASC/entity/ProjectTeamInvite' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'projectUserFullName' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'projectUserEmail' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                )
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
