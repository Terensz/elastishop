<?php

use framework\packages\PaymentPackage\service\OnlinePaymentService;
use framework\packages\WebshopPackage\entity\Shipment;

App::getContainer()->wireService('WebshopPackage/entity/Shipment');
App::getContainer()->wireService('PaymentPackage/service/OnlinePaymentService');
// dump($shipmentDataSet);
// $additionalShipmentCardFooter = 'framework/packages/WebshopPackage/view/Sections/ShipmentsInProgress/AdditionalShipmentCardFooter.php';
include('framework/packages/WebshopPackage/view/Common/ShipmentList/ShipmentList.php');
?>
<?php 
// dump($selectedPaymentMethod);
// dump($shipmentClosed);
?>


<?php if (!$shipmentClosed): ?>

    <?php 
    $paymentMethodCardErrorClass = ' card-success';
    if ($errors['PaymentMethod']['summary']['errorsCount'] > 0) {
        $paymentMethodCardErrorClass = ' card-error';
    }
    $paymentMethodFieldErrorClass = '';
    if ($errors['PaymentMethod']['messages']['paymentMethodValidationMessage']) {
        $paymentMethodFieldErrorClass = ' is-invalid';
    }

    $shipmentStatus = null;
    if (isset($shipmentDataSet[0]['shipment'])) {
        $shipmentStatus = $shipmentDataSet[0]['shipment']['status'];
    }
    // dump($shipmentDataSet);
    ?>
    <?php if (in_array($shipmentStatus, Shipment::STATUS_COLLECTION_USER_ALLOWED_TO_EDIT)): ?>
    <div class="card<?php echo $paymentMethodCardErrorClass; ?>">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <div class="card-header-textContainer">
                <h6 class="mb-0 text-white"><?php echo trans('payment.method'); ?></h6>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="WebshopPackage_PaymentMethod_paymentMethod" class="form-label"><?php echo trans('payment.method'); ?></label>
                <div class="input-group has-validation">
                    <select class="form-select inputField<?php echo $paymentMethodFieldErrorClass; ?>" name="WebshopPackage_PaymentMethod_paymentMethod" id="WebshopPackage_PaymentMethod_paymentMethod" aria-describedby="WebshopPackage_ShipmentHandling_paymentMethod-validationMessage" required="">
                        <option value="null">-- <?php echo trans('please.choose'); ?> --</option>
                        <?php foreach ($paymentMethods as $paymentMethod): ?>
                        <option value="<?php echo $paymentMethod['referenceName']; ?>"<?php if ($selectedPaymentMethod == $paymentMethod['referenceName']) { echo ' selected'; } ?>><?php echo $paymentMethod['displayedName']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback validationMessage" id="WebshopPackage_PaymentMethod_paymentMethod-validationMessage"><?php echo $errors['PaymentMethod']['messages']['paymentMethodValidationMessage']; ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php 
    // dump($errors['BarionCookieConsent']);
    $barionCookieConsentCardErrorClass = ' card-success';
    if ($errors['BarionCookieConsent']['summary']['errorsCount'] > 0) {
        $barionCookieConsentCardErrorClass = ' card-error';
    }
    if ($errors['BarionCookieConsent']['messages']['barionCookieConsentMessage']) {
        // $paymentMethodFieldErrorClass = ' is-invalid';
    }
    ?>
    <?php if ($errors['BarionCookieConsent']['summary']['errorsCount'] > 0 && in_array($shipmentStatus, Shipment::STATUS_COLLECTION_USER_ALLOWED_TO_EDIT)): ?>
    <div class="card<?php echo $barionCookieConsentCardErrorClass; ?>">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <div class="card-header-textContainer">
                <h6 class="mb-0 text-white"><?php echo trans('barion.cookie.consents'); ?></h6>
            </div>
        </div>
        <div class="card-body">
            <?php echo $errors['BarionCookieConsent']['messages']['barionCookieConsentMessage']; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php 
    // dump($errors['Summary']['errorsCount']);
    // dump(App::getContainer()->getUser());
    // dump($paymentParams);
    // dump(App::get());
    // dump($paymentServiceData);

    ?>

    <?php if (isset($paymentServiceData['transaction']['legitimacy']) && $paymentServiceData['transaction']['legitimacy'] == OnlinePaymentService::LEGITIMACY_SANDBOX): ?>
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div class="card-header-textContainer">
                <h6 class="mb-0 text-white"><?php echo trans('information'); ?></h6>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-0">
                <?php echo trans('test.webshop.warning'); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($errors['Summary']['errorsCount'] == 0 && in_array($shipmentStatus, Shipment::STATUS_COLLECTION_USER_ALLOWED_TO_EDIT)): ?>
    <div class="card">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <div class="card-header-textContainer">
                <h6 class="mb-0 text-white"><?php echo trans('paying.for.the.order'); ?></h6>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-0">
                <button type="button" onclick="Webshop.initPaymentModal(event);" class="btn btn-success"><?php echo trans('i.pay'); ?></button>
            </div>
        </div>
    </div>
    <?php endif; ?>
<?php else: ?>
<?php endif; ?>

<script>
    $('document').ready(function() {
    });
</script>