<div id="checkoutWidget-flexibleContent">
<?php
// include('framework/packages/WebshopPackage/view/widget/WebshopCheckoutWidget/'.$outputView);
include('widgetFlexibleContent.php');
// dump($selectedAddress);
?>
</div>

<script>
var WebshopCheckout = {
    advanceForm: <?php echo $advanceForm ? 'true' : 'false'; ?>,
    validateForm: <?php echo $validateForm ? 'true' : 'false'; ?>,
    getParameters: function() {
        return {
            'submitResponseMethodPath': '<?php echo $httpDomain; ?>/webshop/checkoutFlexibleContent',
            // 'submitResponseSelector': '#widgetContainer-mainContent',
            'submitFormName': 'WebshopPackage_checkout_form',
            'addAddressResponseMethodPath': '<?php echo $httpDomain; ?>/webshop/addAddress',
            'changeAddressResponseMethodPath': '<?php echo $httpDomain; ?>/webshop/changeAddress',
            // 'addAddressResponseSelector': '#widgetContainer-mainContent',
            'addAddressFormName': 'WebshopPackage_editAddress_form'
        };
    },
    showOrHideBulkAddContainer: function(event, offerId) {
        event.preventDefault();
        let bulkAddToCartContainerHtmlId = 'WebshopCheckoutBulkAddToCart_container_' + offerId;
        if ($('#' + bulkAddToCartContainerHtmlId).is(':hidden')) {
            $('#' + bulkAddToCartContainerHtmlId).show();
        } else {
            $('#' + bulkAddToCartContainerHtmlId).hide();
        }
    },
    bulkAddToCart: function(event, offerId) {
        LoadingHandler.start();
        let newQuantity = $('#WebshopCheckoutBulkAddToCart_newQuantity_' + offerId).val();
        if (isNaN(newQuantity)) {
            return false;
        }
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/webshop/addToCart',
            'data': {
                'offerId': offerId,
                'newQuantity': newQuantity
            },
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                WebshopSideCartWidget.call(false);
                Structure.throwToast(response.data.toastTitle, response.data.toastBody);
                SideCart.refreshAddtocartButtons(response.data.cartOfferIds);
                // console.log(response);
                // $(params.responseSelector).html(response.view);
                LoadingHandler.stop();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                LoadingHandler.stop();
            },
        });
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
                console.log('response.data.cartExists: ', response.data.cartExists);
                if (response.data.cartExists == false) {
                    console.log('Empty cart. Form submitted.');
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
    submit: function(advanceForm, validateForm, addressId, focusOnField) {
        LoadingHandler.start();
        var params = WebshopCheckout.getParameters();
        var form = $('#' + params.submitFormName);
        var formData = form.serialize();
        var additionalData = {
            'advanceForm': advanceForm,
            'validateForm': validateForm,
            'focusOnField': focusOnField === null ? '' : focusOnField,
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
                $('#checkoutWidget-flexibleContent').html(response.view);
                // console.log(response);
                if (addressId) {
                    var selector = '#WebshopPackage_checkout_address option[value="' + addressId + '"]';
                    $(selector).prop('selected', true);
                }
                LoadingHandler.stop();

                console.log(response.data);
                let advanceForm = response.data.advanceForm;
                if (advanceForm == 'true') {
                    advanceForm = true;
                }
                if (advanceForm == 'false') {
                    advanceForm = false;
                }
                let validateForm = response.data.validateForm;
                if (validateForm == 'true') {
                    validateForm = true;
                }
                if (validateForm == 'false') {
                    validateForm = false;
                }
                WebshopCheckout.advanceForm = advanceForm;
                WebshopCheckout.validateForm = validateForm;

                console.log('focusOnField: ', focusOnField);
                console.log('response.data.focusOnField : ' , response.data.focusOnField );
                if (response.data.focusOnField && response.data.focusOnField != 'null') {
                    $('#' + response.data.focusOnField).focus();
                }
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
    // $('#documentBody').off('focusout', '.inputField');
    // $('#documentBody').off('click', '.inputField');

    // $('#documentBody').on('blur focusout focus', '.inputField', function(e) {
    //     console.log('inputField blur/focusout');
    //     console.log('WebshopCheckout.validateForm: ', WebshopCheckout.validateForm);
    //     console.log($(this).attr('id'));
    //     console.log(e.currentTarget);
    //     console.log(e.relatedTarget);
    //     // if (WebshopCheckout.validateForm) {
    //     //     let inputClicked = null;
    //     //     $('#documentBody').off('focus', '.inputField');
    //     //     $('#documentBody').on('focus', '.inputField', function() {
    //     //         let inputClicked = $(this).attr('id');
    //     //         console.log('inputClicked: ', inputClicked);
    //     //     });
    //     //     WebshopCheckout.submit(false, true, null, inputClicked);
    //     // }
    // });

    // This code is working well on Chrome, but works terrible on Safari.
    // $('#documentBody').off('blur', '.inputField');
    // $('#documentBody').on('blur', '.inputField', function(e) {
    //     // console.log('BLUR!!!');
    //     // console.log(e);
    //     let currentElement = null;
    //     if (e.currentTarget) {
    //         currentElement = $(e.currentTarget).attr('id');
    //     }

    //     let newElement = null;
    //     if (e.relatedTarget) {
    //         newElement = $(e.relatedTarget).attr('id');
    //     }

    //     if (newElement == null) {
    //         // WebshopCheckout.submit(false, true, null, null);
    //     } else {
    //         if (newElement != 'WebshopPackage_checkout_triggerCorporate' && newElement != 'WebshopPackage_checkout_agreement') {
    //             WebshopCheckout.submit(false, true, null, newElement);
    //         }
    //     }
    //     // let current
    // });

    // $('#documentBody').on('click', '.inputField', function(e) {
    //     let leftElement = null;
    //     if (e.relatedTarget) {
    //         leftElement = $(e.relatedTarget).attr('id');
    //     }

    //     let currentElement = null;
    //     if (e.currentTarget) {
    //         currentElement = $(e.currentTarget).attr('id');
    //     }

    //     // WebshopCheckout.submit(false, true, null, currentElement);
    // });

    // $('#WebshopPackage_checkout_mobile').mask('+99999999999999999999');

    $('#documentBody').off('click', '#WebshopPackage_checkout_triggerCorporate');
    $('#documentBody').on('click', '#WebshopPackage_checkout_triggerCorporate', function() {
        // console.log($('#WebshopPackage_checkout_triggerCorporate').is(':checked'));
        if ($('#WebshopPackage_checkout_triggerCorporate').is(':checked')) {
            $('#WebshopPackage_checkout_corporateContainer').show();
        } else {
            $('#WebshopPackage_checkout_corporateContainer').hide();
        }
    });
});
</script>