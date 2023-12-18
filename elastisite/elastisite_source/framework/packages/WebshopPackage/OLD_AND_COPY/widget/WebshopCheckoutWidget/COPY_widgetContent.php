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

    <div style="padding-top: 26px;"></div>
    <div class="article-title">
        <?php echo trans('delivery.address'); ?> *
    </div>
    <div class="article-content">
        <div class="row">
            <div class="col-md-12">
<?php 
// dump($address);
?>
<?php if($registered || !$address): ?>
                <a class="" href="" onclick="WebshopCheckout.addAddress(event)"><?php echo trans('add.new.delivery.address'); ?></a>
<?php elseif (!$registered && $address): ?>
                <a class="" href="" onclick="WebshopCheckout.changeAddress(event)"><?php echo trans('change.delivery.address'); ?></a>
<?php endif ?>
            </div>
        </div>
<?php if($registered): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <select name="WebshopPackage_checkout_address" id="WebshopPackage_checkout_address" class="inputField form-control">
<?php 
        $counter = 0;
        foreach ($userAccount->getPerson()->getAddress() as $address) {
            if ($address->getRepository()->isAvailable($address)) {
?>
        <option value="<?php echo $address->getId(); ?>"<?php echo $address->getId() == $selectedAddress ? ' selected' : '' ?>><?php echo $address; ?></option>
<?php 
                $counter++;
            }
}
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
            <?php echo trans('corporate.order'); ?>
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
                        <input name="WebshopPackage_checkout_taxId" id="WebshopPackage_checkout_taxId" type="text"
                            class="inputField form-control" value="<?php echo $form->getValueCollector()->getValue('taxId', 'displayed'); ?>" aria-describedby="" placeholder="">
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
                        <input name="WebshopPackage_checkout_orgCity" id="WebshopPackage_checkout_orgCity" type="text"
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
                        <input name="WebshopPackage_checkout_orgStreet" id="WebshopPackage_checkout_orgStreet" type="text"
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
                        <input name="WebshopPackage_checkout_orgStreetSuffix" id="WebshopPackage_checkout_orgStreetSuffix" type="text"
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
                        <input name="WebshopPackage_checkout_orgHouseNumber" id="WebshopPackage_checkout_orgHouseNumber" type="text"
                            class="inputField form-control" value="<?php echo $form->getValueCollector()->getValue('orgHouseNumber', 'displayed'); ?>" aria-describedby="" placeholder="">
                    </div>
                    <div class="validationMessage error" id="WebshopPackage_checkout_orgHouseNumber-validationMessage">
                        <?php if ($validateForm): echo $form->getMessage('WebshopPackage_checkout_orgHouseNumber'); endif; ?>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div style="padding-top: 26px;"></div>
    <div class="article-title">
        <?php echo trans('recipient'); ?> *
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

<?php  
// dump($selectedAddress);
?>

<script>
var WebshopCheckout = {
    advanceForm: <?php echo $advanceForm ? 'true' : 'false'; ?>,
    validateForm: <?php echo $validateForm ? 'true' : 'false'; ?>,
    getParameters: function() {
        return {
            'submitResponseMethodPath': '<?php echo $httpDomain; ?>/webshop/checkoutWidget',
            // 'submitResponseSelector': '#widgetContainer-mainContent',
            'submitFormName': 'WebshopPackage_checkout_form',
            'addAddressResponseMethodPath': '<?php echo $httpDomain; ?>/webshop/addAddress',
            'changeAddressResponseMethodPath': '<?php echo $httpDomain; ?>/webshop/changeAddress',
            // 'addAddressResponseSelector': '#widgetContainer-mainContent',
            'addAddressFormName': 'WebshopPackage_editAddress_form'
        };
    },
    addToCart: function(event, offerId) {
        event.preventDefault();
        LoadingHandler.start();
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $httpDomain; ?>/webshop/checkout/addToCart',
            'data': {'offerId': offerId},
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                $('#cartContent').html(response.view);
                Structure.throwToast(response.data.toastTitle, response.data.toastBody);
                LoadingHandler.stop();
                if (response.data.cartExists == false) {
                    WebshopCheckout.submit(false, false, null, null);
                }
                // console.log(response);
                // $(params.responseSelector).html(response.view);
                WebshopCheckout.addOrRemove();
                LoadingHandler.stop();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                LoadingHandler.stop();
            },
        });
    },
    removeFromCart: function(event, offerId) {
        event.preventDefault();
        LoadingHandler.start();
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $httpDomain; ?>/webshop/checkout/removeFromCart',
            'data': {'offerId': offerId},
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                $('#cartContent').html(response.view);
                Structure.throwToast(response.data.toastTitle, response.data.toastBody);
                LoadingHandler.stop();
                if (response.data.cartExists == false) {
                    WebshopCheckout.submit(false, false, null, null);
                }
                WebshopCheckout.addOrRemove();
                LoadingHandler.stop();
                // console.log(response);
                // $(params.responseSelector).html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                LoadingHandler.stop();
            },
        });
    },
    addOrRemove: function() {
        Structure.loadWidget('WebshopCheckoutSideWidget');
    },
    submit: function(advanceForm, validateForm, addressId) {
        LoadingHandler.start();
        var params = WebshopCheckout.getParameters();
        var form = $('#' + params.submitFormName);
        var formData = form.serialize();
        var additionalData = {
            'advanceForm': advanceForm,
            'validateForm': validateForm,
            'submitted': true
        };
        // console.log(additionalData);
        ajaxData = formData + '&' + $.param(additionalData);
        $.ajax({
            'type' : 'POST',
            'url' : params.submitResponseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                $('#widgetContainer-mainContent').html(response.view);
                // console.log(response);
                if (addressId) {
                    var selector = '#WebshopPackage_checkout_address option[value="' + addressId + '"]';
                    $(selector).prop('selected', true);
                }
                LoadingHandler.stop();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    addAddress: function(e) {
        e.preventDefault();
        $('#editorModalBody').html('');
        $('#editorModalLabel').html('<?php echo trans('add.new.delivery.address'); ?>');
        var params = WebshopCheckout.getParameters();
        var form = $('#' + params.addAddressFormName);
        var formData = form.serialize();
        var additionalData = {
            'submitted': true
        };
        ajaxData = formData + '&' + $.param(additionalData);
        $.ajax({
            'type' : 'POST',
            'url' : params.addAddressResponseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                $('#editorModalBody').html(response.view);
                // console.log(response);
                // var params = WebshopSearchAll.getParameters();
                // console.log('success!!!!!!', response);
                // console.log(response);
                // $(params.responseSelector).html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
        $('#editorModal').modal('show');
    },
    changeAddress: function(e) {
        e.preventDefault();
        $('#editorModalBody').html('');
        $('#editorModalLabel').html('<?php echo trans('change.delivery.address'); ?>');
        var params = WebshopCheckout.getParameters();
        var form = $('#' + params.addAddressFormName);
        var formData = form.serialize();
        var additionalData = {
            'submitted': true
        };
        ajaxData = formData + '&' + $.param(additionalData);
        $.ajax({
            'type' : 'POST',
            'url' : params.changeAddressResponseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                console.log(response);
                ElastiTools.checkResponse(response);
                $('#editorModalBody').html(response.view);
                // console.log(response);
                // var params = WebshopSearchAll.getParameters();
                // console.log('success!!!!!!', response);
                // console.log(response);
                // $(params.responseSelector).html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
        $('#editorModal').modal('show');
    }
};

$(document).ready(function() {
    $('.inputField').on('blur', function() {
        if (WebshopCheckout.validateForm) {
            WebshopCheckout.submit(false, true, null, null);
        }
        console.log('inputField blur');
    });
    $('#WebshopPackage_checkout_mobile').mask('+99999999999999999999');

    $('#WebshopPackage_checkout_triggerCorporate').off('click');
    $('#WebshopPackage_checkout_triggerCorporate').on('click', function() {
        // console.log($('#WebshopPackage_checkout_triggerCorporate').is(':checked'));
        if ($('#WebshopPackage_checkout_triggerCorporate').is(':checked')) {
            $('#WebshopPackage_checkout_corporateContainer').show();
        } else {
            $('#WebshopPackage_checkout_corporateContainer').hide();
        }
    });
});
</script>