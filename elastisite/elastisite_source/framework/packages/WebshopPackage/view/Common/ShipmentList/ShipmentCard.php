<?php

use framework\packages\PaymentPackage\service\OnlinePaymentService;
use framework\packages\WebshopPackage\entity\Shipment;

App::getContainer()->wireService('PaymentPackage/service/OnlinePaymentService');
App::getContainer()->wireService('WebshopPackage/entity/Shipment');

if (!isset($cardHeaderStyleClasses)) {
    $cardHeaderStyleClasses = 'bg-primary text-white';
}

$userTypeTranslations = [
    'Guest' => trans('user.type.guest'),
    'User' => trans('user.type.authenticated.user'),
    'Both' => trans('user.type.anyone'),
];
// dump($shipmentDataSetRow['pack']);
?>
    <div class="card">
        <div class="<?php echo $cardHeaderStyleClasses; ?> card-header d-flex justify-content-between align-items-center">
            <div class="card-header-textContainer">
                <h6 class="mb-0 text-white"><?php echo isset($cardTitleText) ? $cardTitleText : trans('order'); ?></h6>
            </div>
        </div>
        <div class="card-body">
            <table>
                <tr>
                    <td class="table-m-1" style="text-align: right;">
                        <?php echo trans('order.started.at'); ?>
                    </td>
                    <td class="table-m-1" width="10px">
                    </td>
                    <td class="table-m-1">
                        <b><?php echo $shipmentDataSetRow['pack']['createdAt']; ?></b>
                    </td>
                </tr>
                <tr>
                    <td class="table-m-1" style="text-align: right;">
                        <?php echo trans('status'); ?>
                    </td>
                    <td class="table-m-1" width="10px">
                    </td>
                    <td class="table-m-1">
                        <b><?php echo $shipmentDataSetRow['pack']['publicStatusText']; ?></b>
                    </td>
                </tr>
                <tr>
                    <td class="table-m-1" style="text-align: right;">
                        <?php echo trans('permitted.user.type'); ?>
                    </td>
                    <td class="table-m-1" width="10px">
                    </td>
                    <td class="table-m-1">
                        <b><?php echo isset($userTypeTranslations[$shipmentDataSetRow['pack']['permittedUserType']]) ? $userTypeTranslations[$shipmentDataSetRow['pack']['permittedUserType']] : $shipmentDataSetRow['pack']['permittedUserType']; ?></b>
                    </td>
                </tr>
                <?php  
                $paymentMethods = OnlinePaymentService::getAvailableGatewayProviders();
                $displayedPaymentMethod = null;
                foreach ($paymentMethods as $availablePaymentMethod) {
                    if ($availablePaymentMethod['referenceName'] == $shipmentDataSetRow['pack']['paymentMethod']) {
                        $displayedPaymentMethod = $availablePaymentMethod['displayedName'];
                    }
                }
                ?>
                <tr>
                    <td class="table-m-1" style="text-align: right;">
                        <?php echo trans('payment.method'); ?>
                    </td>
                    <td class="table-m-1" width="10px">
                    </td>
                    <td class="table-m-1">
                        <b><?php echo $displayedPaymentMethod ? : $shipmentDataSetRow['pack']['paymentMethod']; ?></b>
                    </td>
                </tr>
            </table>
        </div>
        <?php foreach ($shipmentDataSetRow['pack']['packItems'] as $shipmentItem): ?>
            <?php 
            $mainProductImageLink = $shipmentItem['product']['mainProductImageLink'];
            $productName = $shipmentItem['product']['name'];
            $grossItemPriceFormatted = $shipmentItem['product']['actualPrice']['grossItemPriceFormatted'];
            $quantity = $shipmentItem['quantity'];
            $grossUnitPriceFormatted = $shipmentItem['product']['actualPrice']['grossUnitPriceFormatted'];
            $currencyCode = $shipmentItem['product']['actualPrice']['currencyCode'];
            include('framework/packages/WebshopPackage/view/Common/ProductTinyCardFooter/ProductTinyCardFooter.php');
            ?>
        <?php endforeach; ?>
        <div class="card-footer">
        <?php echo in_array($shipmentDataSetRow['pack']['status'], Shipment::STATUS_COLLECTION_UNPAID_STATUSES) ? trans('total.payable') : trans('total.paid'); ?>: <b><?php echo $shipmentDataSetRow['summary']['sumGrossItemPriceFormatted'].' '.$shipmentDataSetRow['pack']['currencyCode']; ?></b>
        </div>
        <?php 
        if (isset($additionalShipmentCardFooter) && $additionalShipmentCardFooter) {
            include($additionalShipmentCardFooter);
        }
        ?>
    </div>