<?php

use framework\packages\FrameworkPackage\service\BasicConstants;
use framework\packages\UXPackage\service\ViewTools;

$configItems = [
    'WebshopPackage_webshopIsActive' => [
        'label' => trans('webshop.is.active'),
        'type' => 'select',
        'options' => [
            'true' => BasicConstants::OPTION_TRUE,
            'false' => BasicConstants::OPTION_FALSE
        ]
    ],
    'WebshopPackage_allowCartMultipleQuantity' => [
        'label' => trans('allow.cart.quantity'),
        'type' => 'select',
        'options' => [
            'true' => BasicConstants::OPTION_TRUE,
            'false' => BasicConstants::OPTION_FALSE
        ]
    ],
    'WebshopPackage_removeCartOnLogin' => [
        'label' => trans('remove.cart.on.login'),
        'type' => 'select',
        'options' => [
            'true' => BasicConstants::OPTION_TRUE,
            'false' => BasicConstants::OPTION_FALSE
        ]
    ],
    'WebshopPackage_homepageListType' => [
        'label' => trans('homepage.list.type'),
        'type' => 'select',
        'options' => $homepageListTypes // well prepared in the controller
    ],
    // 'WebshopPackage_removeTemporaryPersonOnCloseShipment' => [
    //     'label' => trans('remove.temporary.person.on.close.shipment'),
    //     'type' => 'select',
    //     'options' => [
    //         'true' => BasicConstants::OPTION_TRUE,
    //         'false' => BasicConstants::OPTION_FALSE
    //     ]
    // ],
    'WebshopPackage_onlyRegistratedUsersCanCheckout' => [
        'label' => trans('only.registrated.users.can.checkout'),
        'type' => 'select',
        'options' => [
            'true' => BasicConstants::OPTION_TRUE,
            'false' => BasicConstants::OPTION_FALSE
        ]
    ],
    'WebshopPackage_closedShipmentIsEditable' => [
        'label' => trans('closed.shipment.is.editable'),
        'type' => 'select',
        'options' => [
            'true' => BasicConstants::OPTION_TRUE,
            'false' => BasicConstants::OPTION_FALSE
        ]
    ],
    'WebshopPackage_reopenShipmentIsAllowed' => [
        'label' => trans('reopen.shipment.is.allowed'),
        'type' => 'select',
        'options' => [
            'true' => BasicConstants::OPTION_TRUE,
            'false' => BasicConstants::OPTION_FALSE
        ]
    ],
    'WebshopPackage_displayedRunningOrders' => [
        'label' => trans('displayed.running.orders'),
        'type' => 'select',
        'options' => BasicConstants::OPTIONS_20_50_100
    ],
    'WebshopPackage_maxProductsOnPage' => [
        'label' => trans('max.products.on.page'),
        'type' => 'select',
        'options' => BasicConstants::OPTIONS_10_20_50_100
    ]
    // '' => [
    //     'label' => trans(''),
    //     'type' => 'select',
    //     'options' => [
    //         'true' => BasicConstants::OPTION_TRUE,
    //         'false' => BasicConstants::OPTION_FALSE
    //     ]
    // ],
];

?>


<form name="WebshopPackage_editConfig_form" id="WebshopPackage_editConfig_form" method="POST" action="" enctype="multipart/form-data">

<?php 
// dump($settings);
// dump($configItems);
foreach ($configItems as $requestKey => $configRow):
    $options = [];
    foreach ($configRow['options'] as $optionKey => $rawOptions) {
        // dump($rawOptions);exit;
        if (!isset($rawOptions['rawValue'])) {
            dump($rawOptions);
        }
        $displayedOptionValue = $rawOptions['translateDisplayedValue'] ? trans($rawOptions['rawValue']) : $rawOptions['rawValue'];
        // dump($settings[$requestKey]);
        // dump($rawOptions['optionKey']);
        $options[$optionKey] = [
            'translateDisplayedValue' => $rawOptions['translateDisplayedValue'],
            'optionKey' => $rawOptions['optionKey'],
            'displayedValue' => $displayedOptionValue,
            'selected' => $settings[$requestKey] == trans($rawOptions['optionKey'])
        ];
    }
    ViewTools::displayComponent('dashkit/inputs/'.$configRow['type'], [
        'requestKey' => $requestKey,
        'label' => $configRow['label'],
        'options' => $options,
        'isInvalidClassString' => '' // Other situation: ' is-invalid' 
    ]);
endforeach;
?>
</form>

<div class="mb-3">
    <button name="WebshopPackage_editConfig_submit" id="WebshopPackage_editConfig_submit" type="button" class="btn btn-primary" onclick="AdminWebshopConfig.edit(event, true);" value="">
        <?php echo trans('save'); ?>
    </button>
</div>