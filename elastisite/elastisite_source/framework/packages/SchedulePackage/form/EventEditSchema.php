<?php
namespace framework\packages\SchedulePackage\form;

use framework\component\parent\FormSchema;

class EventEditSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return [
            'primaryEntity:Event' => [
                'title' => [
                    'validatorRules' => [
                        'required' => true
                    ]
                ],
                'description' => [
                    'validatorRules' => [
                        'required' => true
                    ]
                ],
                'startDate' => [
                    'validatorRules' => [
                        'required' => true,
                        'dateTime' => true
                    ]
                ],
                'endDate' => [
                    'validatorRules' => [
                        'required' => true,
                        'dateTime' => true,
                        'startDateIsDateTime' => true,
                        'laterThanStartDate' => true
                    ],
                    'compareAttributes' => true
                ],
                'maxSubscribers' => [
                    'validatorRules' => [
                        'required' => true,
                        'integer' => true
                    ]
                ],
                'active' => [
                    'validatorRules' => [
                        'required' => false
                    ]
                ]
            ]
        ];
    }

    public function getSchemaConfig()
    {
        return array(
            'dataRepository' => 'EventRepository',
            'getDataMethod' => 'getEventEditSchemaData',
            'storeDataMethod' => 'storeEventEditSchemaData'
        );
    }

    public function getEntityConfig()
    {
        return [
            'entity' => 'framework/packages/SchedulePackage/entity/Event'
        ];
    }

    public function getCustomValidators()
    {
        return [
            [
                'class' => 'framework/packages/SchedulePackage/form/EventCustomValidator',
                'method' => 'startDateIsDateTime'
            ],
            [
                'class' => 'framework/packages/SchedulePackage/form/EventCustomValidator',
                'method' => 'laterThanStartDate'
            ]
        ];
    }
}
