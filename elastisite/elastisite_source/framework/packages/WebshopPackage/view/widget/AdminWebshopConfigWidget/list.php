<?php

use framework\component\helper\StringHelper;

$tableData = [
    [
        'apellation' => trans('webshop.is.active'),
        'value' => $settings['WebshopPackage_webshopIsActive']
    ],
    [
        'apellation' => trans('allow.cart.quantity'),
        'value' => $settings['WebshopPackage_allowCartMultipleQuantity']
    ],
    [
        'apellation' => trans('remove.cart.on.login'),
        'value' => $settings['WebshopPackage_removeCartOnLogin']
    ],
    [
        'apellation' => trans('homepage.list.type'),
        'value' => trans(StringHelper::alterToTranslationFormat($settings['WebshopPackage_homepageListType']))
    ],
    // [
    //     'apellation' => trans('remove.temporary.person.on.close.shipment'),
    //     'value' => $settings['WebshopPackage_removeTemporaryPersonOnCloseShipment']
    // ],
    [
        'apellation' => trans('only.registrated.users.can.checkout'),
        'value' => $settings['WebshopPackage_onlyRegistratedUsersCanCheckout']
    ],
    [
        'apellation' => trans('closed.shipment.is.editable'),
        'value' => $settings['WebshopPackage_closedShipmentIsEditable']
    ],
    [
        'apellation' => trans('reopen.shipment.is.allowed'),
        'value' => $settings['WebshopPackage_reopenShipmentIsAllowed']
    ],
    [
        'apellation' => trans('displayed.running.orders'),
        'value' => $settings['WebshopPackage_displayedRunningOrders']
    ],
    [
        'apellation' => trans('max.products.on.page'),
        'value' => $settings['WebshopPackage_maxProductsOnPage']
    ],
    // [
    //     'apellation' => trans('product.list.max.cols'),
    //     'value' => $settings['WebshopPackage_productListMaxCols']
    // ]
];
// dump($tableData);
?>

<div class="card table-card">
    <div class="pro-scroll">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-b-0">
                    <thead>
                        <tr>
                            <th><?php echo trans('appellation'); ?></th>
                            <th><?php echo trans('value'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tableData as $tableDataRow): ?>
                        <tr>
                            <td><?php echo $tableDataRow['apellation']; ?></td>
                            <td><?php echo $tableDataRow['value']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
