<?php 
include('framework/packages/WebshopPackage/view/Parts/TestWebshopWarning.php');
?>

    <div class="widgetWrapper" style="padding: 16px;">
    <form name="WebshopPackage_checkout_form" id="WebshopPackage_checkout_form" method="POST" autocomplete="off" action="">
        <div class="article-title">
            <?php echo trans('cart.content'); ?>
        </div>
        <div id="cartContent" class="article-content"><?php include('cartContent.php') ?></div>

        <div style="padding-top: 26px;"></div>
        <!-- <div class="article-title">
            <?php echo trans('payment.method'); ?>
        </div>
        <div class="article-content">
            <div class="row">
                <div class="col-md-12">
                    <?php echo trans('cash.on.delivery'); ?>
                </div>
            </div>
        </div> -->
<?php 
// dump($form);
?>
<?php 
if ($pickUpPointsView) {
    echo $pickUpPointsView;
}
?>
<?php if ($fillingAddressIsRequired): ?>
        <div style="padding-top: 26px;"></div>
        <div class="article-title">
            <?php echo trans('delivery.address'); ?> *
        </div>
        <div class="article-content">
            <div class="row">
                <div class="col-md-12">
    <?php if($registered || !$address): ?>
                    <a class="" href="" onclick="WebshopCheckout.addAddress(event)"><?php echo trans('add.new.delivery.address'); ?></a>
    <?php elseif (!$registered && $address): ?>
                    <a class="" href="" onclick="WebshopCheckout.changeAddress(event)"><?php echo trans('change.delivery.address'); ?></a>
    <?php endif ?>
                </div>
            </div>
<?php 
/**
 * Now $registered is false anyway. @todo 
*/
?>
    <?php if($registered): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <select name="WebshopPackage_checkout_address" id="WebshopPackage_checkout_address" class="inputField form-control">
<?php 
        $counter = 0;
        foreach ($userAccount->getPerson()->getAddress() as $address):
            if ($address->getRepository()->isAvailable($address)):
?>
                            <option value="<?php echo $address->getId(); ?>"<?php echo $address->getId() == $selectedAddress ? ' selected' : '' ?>><?php echo $address; ?></option>
<?php 
                $counter++;
            endif;
        endforeach;
?>
                        </select>
                    </div>
                </div>
            </div>
    <?php else: ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?php echo $address ? $address : null; ?>
                    </div>
                    <div class="validationMessage error" id="WebshopPackage_checkout_address-validationMessage" style="padding-top:0px;">
                        <?php if ($validateForm && $addressMessage) { echo $addressMessage; } ?>
                    </div>
                </div>
            </div>
    <?php endif ?>

        </div>
<?php endif ?>

<?php if ($corporateShipmentEnabled): ?>
        <div style="padding-top: 26px;"></div>
        <div class="article-title">
            <?php echo trans('trigger.corporate'); ?>
        </div>    
        <div class="article-content">
            <div class="row">
                <div class="col-md-12">
                    <div style="margin-left: 20px; float: left;" class="form-group form-check">
                        <input type="checkbox" class="form-check-input" value="1" id="WebshopPackage_checkout_triggerCorporate" name="WebshopPackage_checkout_triggerCorporate" <?php if ($form->getValueCollector()->getValue('triggerCorporate', 'displayed')) { echo 'checked'; } ?>>
                    </div>
                    <?php echo trans('trigger.corporate.description'); ?>
                    <div class="validationMessage error" id="WebshopPackage_checkout_triggerCorporate-validationMessage" style="padding-top:0px;">
                        <?php if ($validateForm): echo $form->getMessage('WebshopPackage_checkout_triggerCorporate'); endif; ?>
                    </div>
                </div>
            </div>
            <div id="WebshopPackage_checkout_corporateContainer" style="<?php if (!$form->getValueCollector()->getValue('triggerCorporate', 'displayed')) { echo 'display: none;'; } ?>">

            <div class="article-title">
                <?php echo trans('corporate.order.data'); ?>
            </div>


                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-12">
                        <b><?php echo trans('organization.name'); ?> *</b>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" style="padding-bottom: 0px; margin-bottom: 0px;">
                            <input name="WebshopPackage_checkout_organizationName" id="WebshopPackage_checkout_organizationName" type="text"
                                class="inputField form-control" value="<?php echo $form->getValueCollector()->getValue('organizationName', 'displayed'); ?>" aria-describedby="" placeholder="">
                        </div>
                        <div class="validationMessage error" id="WebshopPackage_checkout_organizationName-validationMessage">
                            <?php if ($validateForm): echo $form->getMessage('WebshopPackage_checkout_organizationName'); endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-12">
                        <b><?php echo trans('organization.tax.id'); ?> *</b>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" style="padding-bottom: 0px; margin-bottom: 0px;">
                            <input name="WebshopPackage_checkout_taxId" id="WebshopPackage_checkout_taxId" type="text" maxlength="13"
                                class="inputField form-control" value="<?php echo $form->getValueCollector()->getValue('taxId', 'displayed'); ?>" aria-describedby="" placeholder="<?php echo trans('e.g') ?>: 12345678-1-41">
                        </div>
                        <div class="validationMessage error" id="WebshopPackage_checkout_taxId-validationMessage">
                            <?php if ($validateForm): echo $form->getMessage('WebshopPackage_checkout_taxId'); endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-12">
                        <b><?php echo trans('organization.country'); ?> *</b>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" style="padding-bottom: 0px; margin-bottom: 0px;">
                            <div class="input-group">
                                <select name="WebshopPackage_checkout_orgCountry" id="WebshopPackage_checkout_orgCountry" class="inputField form-control">

                                    <option value="348" selected="">Magyarország</option>

                                </select>
                            </div>
                        </div>
                        <div class="validationMessage error" id="WebshopPackage_checkout_orgCountry-validationMessage">
                            <?php if ($validateForm): echo $form->getMessage('WebshopPackage_checkout_orgCountry'); endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-12">
                        <b><?php echo trans('organization.zip.code'); ?> *</b>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" style="padding-bottom: 0px; margin-bottom: 0px;">
                            <input name="WebshopPackage_checkout_orgZipCode" id="WebshopPackage_checkout_orgZipCode" type="text"
                                class="inputField form-control" value="<?php echo $form->getValueCollector()->getValue('orgZipCode', 'displayed'); ?>" aria-describedby="" placeholder="">
                        </div>
                        <div class="validationMessage error" id="WebshopPackage_checkout_orgZipCode-validationMessage">
                            <?php if ($validateForm): echo $form->getMessage('WebshopPackage_checkout_orgZipCode'); endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-12">
                        <b><?php echo trans('organization.city'); ?> *</b>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" style="padding-bottom: 0px; margin-bottom: 0px;">
                            <input name="WebshopPackage_checkout_orgCity" id="WebshopPackage_checkout_orgCity" type="text" maxlength="100"
                                class="inputField form-control" value="<?php echo $form->getValueCollector()->getValue('orgCity', 'displayed'); ?>" aria-describedby="" placeholder="">
                        </div>
                        <div class="validationMessage error" id="WebshopPackage_checkout_orgCity-validationMessage">
                            <?php if ($validateForm): echo $form->getMessage('WebshopPackage_checkout_orgCity'); endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-12">
                        <b><?php echo trans('organization.street'); ?> *</b>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" style="padding-bottom: 0px; margin-bottom: 0px;">
                            <input name="WebshopPackage_checkout_orgStreet" id="WebshopPackage_checkout_orgStreet" type="text" maxlength="100"
                                class="inputField form-control" value="<?php echo $form->getValueCollector()->getValue('orgStreet', 'displayed'); ?>" aria-describedby="" placeholder="">
                        </div>
                        <div class="validationMessage error" id="WebshopPackage_checkout_orgStreet-validationMessage">
                            <?php if ($validateForm): echo $form->getMessage('WebshopPackage_checkout_orgStreet'); endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-12">
                        <b><?php echo trans('organization.street.suffix'); ?> *</b>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" style="padding-bottom: 0px; margin-bottom: 0px;">
                            <input name="WebshopPackage_checkout_orgStreetSuffix" id="WebshopPackage_checkout_orgStreetSuffix" type="text" maxlength="30"
                                class="inputField form-control" value="<?php echo $form->getValueCollector()->getValue('orgStreetSuffix', 'displayed'); ?>" aria-describedby="" placeholder="">
                        </div>
                        <div class="validationMessage error" id="WebshopPackage_checkout_orgStreetSuffix-validationMessage">
                            <?php if ($validateForm): echo $form->getMessage('WebshopPackage_checkout_orgStreetSuffix'); endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-12">
                        <b><?php echo trans('organization.house.number'); ?> *</b>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" style="padding-bottom: 0px; margin-bottom: 0px;">
                            <input name="WebshopPackage_checkout_orgHouseNumber" id="WebshopPackage_checkout_orgHouseNumber" type="text" maxlength="20"
                                class="inputField form-control" value="<?php echo $form->getValueCollector()->getValue('orgHouseNumber', 'displayed'); ?>" aria-describedby="" placeholder="">
                        </div>
                        <div class="validationMessage error" id="WebshopPackage_checkout_orgHouseNumber-validationMessage">
                            <?php if ($validateForm): echo $form->getMessage('WebshopPackage_checkout_orgHouseNumber'); endif; ?>
                        </div>
                    </div>
                </div>


            </div>
        </div>
<?php endif ?>

        <div style="padding-top: 26px;"></div>
        <div class="article-title">
            <?php echo trans('recipient.or.customer'); ?> *
        </div>

<?php if ($isWebshopTestMode): ?>
        <div class="widgetWrapper-danger">
            <?php echo trans('please.add.false.name'); ?>
        </div>
<?php endif; ?>

        <div class="article-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" style="padding-bottom: 0px; margin-bottom: 0px;">
                        <input name="WebshopPackage_checkout_recipient" id="WebshopPackage_checkout_recipient" type="text"
                            class="inputField form-control" value="<?php echo $recipient; ?>" aria-describedby="" placeholder="">
                    </div>
                    <div class="validationMessage error" id="WebshopPackage_checkout_recipient-validationMessage" style="padding-top:0px;">
                        <?php if ($validateForm): echo $form->getMessage('WebshopPackage_checkout_recipient'); endif; ?>
                    </div>
                </div>
            </div>
        </div>

<?php if (!$registered): ?>

        <div style="padding-top: 26px;"></div>
        <div class="article-title">
            <?php echo trans('email'); ?> *
        </div>
        <div class="article-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" style="padding-bottom: 0px; margin-bottom: 0px;">
                        <input name="WebshopPackage_checkout_email" id="WebshopPackage_checkout_email" type="text"
                            class="inputField form-control" value="<?php echo $form->getValueCollector()->getValue('email', 'displayed'); ?>" aria-describedby="" placeholder="">
                    </div>
                    <div class="validationMessage error" id="WebshopPackage_checkout_email-validationMessage" style="padding-top:0px;">
                        <?php if ($validateForm): echo $form->getMessage('WebshopPackage_checkout_email'); endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding-top: 26px;"></div>
        <div class="article-title">
            <?php echo trans('mobile'); ?> *
        </div>

<?php if ($isWebshopTestMode): ?>
        <div class="widgetWrapper-danger">
            <?php echo trans('please.add.real.email.to.recieve.confirmation'); ?>
        </div>
<?php endif; ?>

        <div class="article-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" style="padding-bottom: 0px; margin-bottom: 0px;">
                        <input name="WebshopPackage_checkout_mobile" id="WebshopPackage_checkout_mobile" type="text"
                            class="inputField form-control" value="<?php echo $form->getValueCollector()->getValue('mobile', 'displayed'); ?>" aria-describedby="" placeholder="">
                    </div>
                    <div class="validationMessage error" id="WebshopPackage_checkout_mobile-validationMessage" style="padding-top:0px;">
                        <?php if ($validateForm): echo $form->getMessage('WebshopPackage_checkout_mobile'); endif; ?>
                    </div>
                </div>
            </div>
        </div>

<?php endif ?>

        <div style="padding-top: 26px;"></div>
        <div class="article-title">
            <?php echo trans('notice'); ?>
        </div>
        <div class="article-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" style="padding-bottom: 0px; margin-bottom: 0px;">
                        <input name="WebshopPackage_checkout_notice" id="WebshopPackage_checkout_notice" type="text"
                            class="inputField form-control" value="<?php echo $form->getValueCollector()->getValue('notice', 'displayed'); ?>" aria-describedby="" placeholder="">
                    </div>
                </div>
            </div>
        </div>

        <div style="padding-top: 26px;"></div>
        <div class="article-title">
            <?php echo trans('accepting.terms'); ?> *
        </div>
        <div class="article-content">
            <div class="row">
                <div class="col-md-12">
                    <div style="margin-left: 20px; float: left;" class="form-group form-check">
                        <input type="checkbox" class="form-check-input" value="1" id="WebshopPackage_checkout_agreement" name="WebshopPackage_checkout_agreement" <?php if ($form->getValueCollector()->getValue('agreement', 'displayed')) { echo 'checked'; } ?>>
                    </div>
                    <?php echo trans('order.agreement'); ?>
                    <div class="validationMessage error" id="WebshopPackage_checkout_agreement-validationMessage" style="padding-top:0px;">
                        <?php if ($validateForm): echo $form->getMessage('WebshopPackage_checkout_agreement'); endif; ?>
                    </div>
                </div>
            </div>

            <div style="padding-top: 26px;"></div>

            <div class="row">
                <div class="col-md-12">
                    <button id="WebshopPackage_checkout_submit" style="width: 320px;"
                    type="button" class="btn btn-secondary btn-block"
                    onclick="WebshopCheckout.submit(true, true, null, null);"><?php echo trans('i.order'); ?></button>
                </div>
            </div>
        </div>

    </form>
    </div>

<script>
$(document).ready(function() {
    $('#WebshopPackage_checkout_mobile').mask('+99999999999999999999');
});
</script>