<?php
namespace projects\ASC\form;

use App;
use framework\component\parent\FormSchema;
use projects\ASC\entity\AscScale;

class EditProjectTeamSchema extends FormSchema
{
    // public function getEntitySpecs()
    // {
    //     App::getContainer()->wireService('projects/ASC/entity/AscScale');
    //     return array(
    //         'primaryEntity:projects/ASC/entity/ProjectTeam' => array(
    //             'id' => array(
    //                 'technical' => true,
    //                 'primaryKey' => true
    //             ),
    //             'name' => array(
    //                 'validatorRules' => array(
    //                     'required' => true
    //                 )
    //             ),
    //             'childrenIncluded' => array(
    //                 'validatorRules' => array(
    //                     'required' => true
    //                 )
    //             ),
    //             'entity:projects/ASC/entity/AscUnit' => array(
    //                 'ascUnitId' => array(
    //                     'property' => 'id',
    //                     'validatorRules' => array(
    //                         'required' => false
    //                     )
    //                 )
    //             )
    //         )
    //     );
    // }

    public function getEntitySpecs()
    {
        App::getContainer()->wireService('projects/ASC/entity/AscScale');
        return array(
            'primaryEntity:projects/ASC/entity/ProjectTeam' => array(
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'name' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'childrenIncluded' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'subject' => array(
                    'technical' => true,
                    'validatorRules' => array(
                        'required' => false
                    )
                ),
                'ascUnitId' => array(
                    'technical' => true,
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
