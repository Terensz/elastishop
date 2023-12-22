<?php ?>
<style>
#webshop_navbar {
    position: -webkit-sticky !important;
    position: sticky !important;
    top: 0;
    /* align-self: flex-start;
    overflow-y: auto; */
    /* z-index: 1000; */
}
</style>

<div style="display: flex;" class="flex-container">
    <div class="flex-content-sidebar" id="AscScaleBuilder_PrimarySubjectBar_container" style="width: 380px; height: 100% !important;">
        <div id="webshop_navbar" class="navbar-wrapper" style="width: 100%; height: 100% !important;">
            <div class="navbar-contentss ps">
                [Sidebar]
            </div>
        </div>
    </div>
    <div class="flex-content-main pc-container">
        <div class="pcoded-content card-container">
            <div id="Webshop_Main">
                [MainContent]
            </div>
        </div>
    </div>
</div>
<?php 
$messages = App::getContainer()->getSession()->getMessages();
// dump($messages);
?>
<script>
var Webshop = {
    processResponse: function(response, calledBy, onSuccessCallback) {
        console.log('Webshop.processResponse()');
        dump(response);
        if (typeof this[onSuccessCallback] === 'function') {
            this[onSuccessCallback](response);
        }
        LoadingHandler.stop();
    },
    callAjax: function(calledBy, ajaxUrl, additionalData, onSuccessCallback) {
        let baseData = {};
        let ajaxData = $.extend({}, baseData, additionalData);
        LoadingHandler.start();
        $.ajax({
            'type' : 'POST',
            'url' : ajaxUrl,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                Webshop.processResponse(response, calledBy, onSuccessCallback);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    addToCartInit: function(event, offerId) {
        if (event) {
            event.preventDefault();
        }
        Webshop.callAjax('addToCartInit', '/webshop/addToCart', {
            'offerId': offerId
        }, 'addToCartCallback');
    },
    addToCartCallback: function(response) {
        // We not use this now, because the triggering link has ajaxCallerLink class,
        // so at the end of loading the structure will be automatically reloaded.
    },
    setCartItemQuantityInit: function(event, offerId) {
        if (event) {
            event.preventDefault();
        }
        Webshop.callAjax('addToCartInit', '/webshop/setCartItemQuantity', {
            'offerId': offerId,
            'submitted': false
        }, 'setCartItemQuantityInitCallback');
    },
    setCartItemQuantityInitCallback: function(response) {
        $('#editorModalLabel').html('');
        $('#editorModalBody').html('');
        $('#smallModalLabel').html('<?php echo trans('setting.cart.item.quantity'); ?>');
        $('#smallModalBody').html(response.renderedSections.sectionsResponse['SetCartItemQuantityModal']['view']);
        let responseData = response.renderedSections.sectionsResponse['SetCartItemQuantityModal']['data'];
        $('#smallModalConfirm').attr('onclick', "Webshop.setCartItemQuantitySubmit(event, " + responseData.offerId + ", true);");
        $('#smallModal').modal('show');
    },
    setCartItemQuantitySubmit: function(event, offerId, closeModal) {
        if (event) {
            event.preventDefault();
        }
        let data = {
            'offerId': offerId,
            'newQuantity': $('#WebshopPackage_SetCartItemQuantity_newQuantity').val(),
            'submitted': true
        };
        // console.log('setCartItemQuantitySubmit data:');
        // console.log(data);
        if (closeModal) {
            $('#editorModal').modal('hide');
        }
        Webshop.callAjax('setCartItemQuantitySubmit', '/webshop/setCartItemQuantity', data, (closeModal ? 'setCartItemQuantitySubmitCallback' : 'setProductDetailsCartItemQuantitySubmitCallback'));
    },
    /*
    It's called by the product info modal
    */
    setProductDetailsCartItemQuantitySubmitCallback: function(response) {
        // console.log('setProductDetailsCartItemQuantitySubmitCallback');
        let responseData = response.renderedSections.sectionsResponse['SetCartItemQuantityModal']['data'];
        Structure.throwToast(responseData.toastTitle, responseData.toastBody);
        Structure.call(window.location.href, false, true, false);
    },
    /*
    It's called by the standalone quantity modal
    */
    setCartItemQuantitySubmitCallback: function(response) {
        console.log(response);
        $('#smallModal').modal('hide');
        Structure.call(window.location.href);
        let responseData = response.renderedSections.sectionsResponse['SetCartItemQuantityModal']['data'];
        Structure.throwToast(responseData.toastTitle, responseData.toastBody);
    },
    showProductDetailsModalInit: function(event, productId) {
        // console.log('showProductDetailsModalInit!!!');
        if (event) {
            event.preventDefault();
        }
        Webshop.callAjax('showProductDetailsModalInit', '/webshop/productDetailsModal', {
            'productId': productId
        }, 'showProductDetailsModalCallback');
    },
    showProductDetailsModalCallback: function(response) {
        console.log(response);
        $('#smallModalLabel').html('');
        $('#smallModalBody').html('');
        $('#editorModalBody').html(response.renderedSections.sectionsResponse['ProductDetailsModal']['view']);
        let responseData = response.renderedSections.sectionsResponse['ProductDetailsModal']['data'];
        $('#editorModalLabel').html(responseData.modalLabel);
        // let responseData = response.renderedSections.sectionsResponse['ProductDetailsModal']['data'];
        // $('#editorModalConfirm').attr('onclick', "Webshop.setCartItemQuantitySubmit(event, " + responseData.offerId + ");");
        $('#editorModal').modal('show');
        // Structure.call(window.location.href);
    },
    search: function(event) {
        if (event) {
            event.preventDefault();
        }
        if ($('#webshop_search_term').val() != '') {
            let linkBase = $('#webshop_search_linkBase').val();
            if (typeof(linkBase) == 'undefined') {
                linkBase = '<?php echo $searchLinkData['searchLinkBaseAll']; ?>';
            }
            let searchTerm = $('#webshop_search_term').val();
            let searchLink = linkBase + searchTerm;
            $('#webshopSearchLink').prop('href', searchLink);
            console.log('webshopSearchLink: ', document.getElementById("webshopSearchLink").outerHTML);
            $('#webshopSearchLink').click();
        } else {
            console.log('empty string <?php echo $searchLinkData['searchLinkBase']; ?>');
            $('#webshopSearchLink').prop('href', '<?php echo $searchLinkData['searchLinkBase']; ?>');
            // console.log("Structure.call('<?php echo $searchLinkData['searchLinkBase']; ?>');");
            // Structure.call('<?php echo $searchLinkData['searchLinkBase']; ?>');
            console.log('webshopSearchLink: ', document.getElementById("webshopSearchLink").outerHTML);
            $('#webshopSearchLink').click();
        }
    },
    selectCustomerType: function(customerType) {
        Webshop.callAjax('selectCustomerType', '/webshop/checkout/selectCustomerType', {
            'customerType': customerType,
        }, 'selectCustomerTypeCallback');
    },
    selectCustomerTypeCallback: function(response) {
        Structure.call(window.location.href);
    },
    addOrganizationInit: function(event) {
        if (event) {
            event.preventDefault();
        }
        Webshop.callAjax('addOrganizationInit', '/webshop/checkout/addOrganization', {
        }, 'editOrganizationCallback');
    },
    editOrganizationInit: function(event, id) {
        if (event) {
            event.preventDefault();
        }
        Webshop.callAjax('editOrganizationInit', '/webshop/checkout/editOrganization', {
            'id': id,
            'submitted': false
        }, 'editOrganizationCallback');
    },
    editOrganizationCallback: function(response) {
        // Structure.call(window.location.href);
        console.log(response);
        $('#smallModalLabel').html('');
        $('#smallModalBody').html('');
        $('#editorModalBody').html(response.renderedSections.sectionsResponse['EditOrganizationModal']['view']);
        let responseData = response.renderedSections.sectionsResponse['EditOrganizationModal']['data'];
        $('#editorModalLabel').html(responseData.modalLabel);
        // let responseData = response.renderedSections.sectionsResponse['ProductDetailsModal']['data'];
        // $('#editorModalConfirm').attr('onclick', "Webshop.setCartItemQuantitySubmit(event, " + responseData.offerId + ");");
        $('#editorModal').modal('show');
    },
    editOrganizationSubmit: function(event, id) {
        if (event) {
            event.preventDefault();
        }
        let ajaxData = {
            'id': id,
            'submitted': true
        };
        let form = $('#WebshopPackage_editOrganization_form');
        let formData = form.serializeArray();
        $.each(formData, function(index, field){
            ajaxData[field.name] = field.value;
        });

        Webshop.callAjax('editOrganizationSubmit', '/webshop/checkout/editOrganization', ajaxData, 'editOrganizationSubmitCallback');
    },
    editOrganizationSubmitCallback: function(response) {
        $('#editorModalBody').html(response.renderedSections.sectionsResponse['EditOrganizationModal']['view']);
        let responseData = response.renderedSections.sectionsResponse['EditOrganizationModal']['data'];
        console.log(responseData);
        if (responseData.success == true) {
            $('#editorModalLabel').html('');
            $('#editorModalBody').html('');
            $('#editorModal').modal('hide');
            Structure.call(window.location.href);
        }
        $('#editorModalLabel').html(responseData.modalLabel);
    },
    selectOrganization: function(id) {
        Webshop.callAjax('selectOrganization', '/webshop/checkout/selectOrganization', {
            'id': id
        }, 'selectOrganizationCallback');
    },
    selectOrganizationCallback: function(response) {
        // console.log(response);
        Structure.call(window.location.href);
    },
    addAddressInit: function(event) {
        if (event) {
            event.preventDefault();
        }
        Webshop.callAjax('addOrganizationInit', '/webshop/checkout/addAddress', {
            'submitted': false
        }, 'editAddressCallback');
    },
    editAddressInit: function(event, id) {
        if (event) {
            event.preventDefault();
        }
        Webshop.callAjax('addOrganizationInit', '/webshop/checkout/editAddress', {
            'id': id,
            'submitted': false
        }, 'editAddressCallback');
    },
    editAddressCallback: function(response) {
        console.log(response);
        // Structure.call(window.location.href);
        // console.log(response);
        // console.log(response.renderedSections.sectionsResponse['EditAddressModal']['view']);
        $('#smallModalLabel').html('');
        $('#smallModalBody').html('');
        $('#editorModalBody').html(response.renderedSections.sectionsResponse['EditAddressModal']['view']);
        let responseData = response.renderedSections.sectionsResponse['EditAddressModal']['data'];
        console.log(responseData);
        $('#editorModalLabel').html(responseData.modalLabel);
        // let responseData = response.renderedSections.sectionsResponse['ProductDetailsModal']['data'];
        // $('#editorModalConfirm').attr('onclick', "Webshop.setCartItemQuantitySubmit(event, " + responseData.offerId + ");");
        $('#editorModal').modal('show');
    },
    editAddressSubmit: function(event, id) {
        if (event) {
            event.preventDefault();
        }
        let ajaxData = {
            'id': id,
            'submitted': true
        };
        let form = $('#WebshopPackage_editAddress_form');
        let formData = form.serializeArray();
        $.each(formData, function(index, field){
            ajaxData[field.name] = field.value;
        });

        Webshop.callAjax('editAddressSubmit', '/webshop/checkout/editAddress', ajaxData, 'addAddressSubmitCallback');
    },
    addAddressSubmitCallback: function(response) {
        // console.log(response);
        // console.log(response.renderedSections.sectionsResponse['EditAddressModal']['view']);
        $('#editorModalBody').html(response.renderedSections.sectionsResponse['EditAddressModal']['view']);
        let responseData = response.renderedSections.sectionsResponse['EditAddressModal']['data'];
        $('#editorModalLabel').html(responseData.modalLabel);
        if (responseData.success == true) {
            $('#editorModalLabel').html('');
            $('#editorModalBody').html('');
            $('#editorModal').modal('hide');
            Structure.call(window.location.href);
        }
    },
    selectAddress: function(id) {
        Webshop.callAjax('selectAddress', '/webshop/checkout/selectAddress', {
            'id': id
        }, 'selectAddressCallback');
    },
    selectAddressCallback: function(response) {
        // console.log(response);
        Structure.call(window.location.href);
    },
    acceptTermsAndConditions: function(id) {
        Webshop.callAjax('selectAddress', '/webshop/checkout/acceptTermsAndConditions', {
            'id': id
        }, 'acceptTermsAndConditionsCallback');
    },
    acceptTermsAndConditionsCallback: function(response) {
        // console.log(response);
        Structure.call(window.location.href);
    },
    saveDeliveryInformation: function(event) {
        if (event) {
            event.preventDefault();
        }
        Webshop.callAjax('saveDeliveryInformation', '/webshop/checkout/saveDeliveryInformation', {
            'recipientName': $('#WebshopPackage_checkout_recipientName').val(),
            'email': $('#WebshopPackage_checkout_email').val(),
            'mobile': $('#WebshopPackage_checkout_mobile').val(),
            'customerNote': $('#WebshopPackage_checkout_customerNote').val()
        }, 'saveDeliveryInformationCallback');
    },
    saveDeliveryInformationCallback: function(response) {
        Structure.call(window.location.href);
    },
    finishCheckout: function(event) {
        if (event) {
            event.preventDefault();
        }
        Webshop.callAjax('finishCheckout', '/webshop/checkout/finishCheckout', {
        }, 'finishCheckoutCallback');
    },
    finishCheckoutCallback: function(response) {
        Structure.call('/webshop/shipment/handling/' + response.data.shipmentCode);
    },
    // ShipmentHandling
    setPaymentMethod: function() {
        console.log('Webshop.setPaymentMethod');
        Webshop.callAjax('setPaymentMethod', '/webshop/shipment/setPaymentMethod', {
            'paymentMethod': $('#WebshopPackage_PaymentMethod_paymentMethod').val()
        }, 'setPaymentMethodCallback');
    },
    setPaymentMethodCallback: function() {
        Structure.call(window.location.href);
    },
    bindShipmentToAccount: function(event, shipmentCode) {
        if (event) {
            event.preventDefault();
        }
        Webshop.callAjax('connectShipmentToAccount', '/webshop/shipment/bindShipmentToAccount', {
            'shipmentCode': shipmentCode
        }, 'bindShipmentToAccountCallback');
    },
    bindShipmentToAccountCallback: function() {
        Structure.call(window.location.href);
    },
    initPaymentModal: function(event) {
        if (event) {
            event.preventDefault();
        }
        console.log('Webshop.initPaymentModal');
        Webshop.callAjax('initPaymentModal', '/webshop/shipment/paymentModal', {
        }, 'initPaymentModalCallback');
    },
    initPaymentModalCallback: function(response) {
        let responseData = response.renderedSections.sectionsResponse['PaymentModal']['data'];
        if (responseData.success == false) {
            Structure.throwErrorToast('<?php echo trans('system.message'); ?>', '<?php echo trans('futile.attempt'); ?>');
        } else {
            $('#smallModalLabel').html('');
            $('#smallModalBody').html('');
            $('#editorModalBody').html(response.renderedSections.sectionsResponse['PaymentModal']['view']);
            // console.log(responseData);
            $('#editorModalLabel').html(responseData.modalLabel);
            $('#editorModal').modal('show');
        }
    },
}

var WebshopProductListAjaxTimer = {
    timeoutStarted: false,
    lastButtonPress: null,
    check: function() {
        var now = new Date().getTime();
        var result = false;
        if ((now - WebshopProductListAjaxTimer.lastButtonPress) > 400) {
            result = true;
        }
        return result;
    },
    timedAjaxReload: function() {
        // console.log('reload!!!');
        var now = new Date().getTime();
        var result = false;
        WebshopProductListAjaxTimer.lastButtonPress = now;
        if (WebshopProductListAjaxTimer.timeoutStarted === false) {
            WebshopProductListAjaxTimer.timeoutStarted = true;
            setTimeout(function () {
                result = WebshopProductListAjaxTimer.check();
                WebshopProductListAjaxTimer.timeoutStarted = false;
                if (result === false) {
                    return WebshopProductListAjaxTimer.timedAjaxReload();
                } else {
                    // WebshopSearch.search();
                }
            }, 800);
        }
        return result;
    }
};


var DeliveryInformation = {
    updateSaveButtonVisibility: function() {
        var recipientNameValue = $('#WebshopPackage_checkout_recipientName').val();
        var customerNoteValue = $('#WebshopPackage_checkout_customerNote').val();
        var emailValue = $('#WebshopPackage_checkout_email').val();
        var mobileValue = $('#WebshopPackage_checkout_mobile').val();

        if (recipientNameValue || customerNoteValue || mobileValue || emailValue) {
            $('#WebshopPackage_checkout_saveDeliveryInformation_container').show();
        } else {
            $('#WebshopPackage_checkout_saveDeliveryInformation_container').hide();
        }
    }
};

$(document).ready(function() {
<?php if (!empty($messages) && isset($messages['cartUpdated'])): ?>
Structure.throwToast('<?php echo trans('system.message'); ?>', '<?php echo $messages['cartUpdated']['body']; ?>');
<?php  
App::getContainer()->getSession()->readMessage('cartUpdated');
?>
<?php endif; ?>

    $('#WebshopPackage_PaymentMethod_paymentMethod').off('change');
    $('#WebshopPackage_PaymentMethod_paymentMethod').on('change', function() {
        Webshop.setPaymentMethod();
    });

    $('#WebshopPackage_checkout_recipientName').off('input');
    $('#WebshopPackage_checkout_recipientName').on('input', function() {
        console.log('WebshopPackage_checkout_recipient');
        DeliveryInformation.updateSaveButtonVisibility();
    });
    $('#WebshopPackage_checkout_customerNote').off('input');
    $('#WebshopPackage_checkout_customerNote').on('input', function() {
        DeliveryInformation.updateSaveButtonVisibility();
    });
    $('#WebshopPackage_checkout_email').off('input');
    $('#WebshopPackage_checkout_email').on('input', function() {
        DeliveryInformation.updateSaveButtonVisibility();
    });
    $('#WebshopPackage_checkout_mobile').off('input');
    $('#WebshopPackage_checkout_mobile').on('input', function() {
        DeliveryInformation.updateSaveButtonVisibility();
    });

    $('.WebshopPackage_checkout_customerType_option').off('click');
    $('.WebshopPackage_checkout_customerType_option').on('click', function() {
        // if ($(this).is(':checked')) {
        //     // A radio gomb kiválasztva van
        //     let id = $(this).attr('id');
        //     console.log(id);
        //     // Webshop.selectOrganization(id);
        //     // console.log('WebshopPackage_checkout_customerType_option click');
        // } else {
        //     // A radio gomb nincs kiválasztva
        //     console.log('A radio gomb nincs kiválasztva');
        // }
        let originalValue = $('#WebshopPackage_checkout_customerType_original');
        let requestedValue = $(this).attr('data-customertype');
        if (requestedValue !== originalValue) {
            Webshop.selectCustomerType(requestedValue);
        }
        // console.log(id);
        // Webshop.selectOrganization(id);
        // console.log('WebshopPackage_checkout_customerType_option click');
    });

    $('.WebshopPackage_checkout_organization_option-triggerModal').off('click');
    $('.WebshopPackage_checkout_organization_option-triggerModal').on('click', function() {
        let id = $(this).attr('data-id');
        Webshop.editOrganizationInit(null, id);
        console.log('WebshopPackage_checkout_organization_option click');
    });

    $('.WebshopPackage_checkout_organization_option').off('click');
    $('.WebshopPackage_checkout_organization_option').on('click', function() {
        let id = $(this).attr('data-id');
        Webshop.selectOrganization(id);
        console.log('WebshopPackage_checkout_organization_option click');
    });

    $('.WebshopPackage_checkout_address_option-triggerModal').off('click');
    $('.WebshopPackage_checkout_address_option-triggerModal').on('click', function() {
        let id = $(this).attr('data-id');
        Webshop.editAddressInit(null, id);
        console.log('WebshopPackage_checkout_organization_option click');
    });

    $('.WebshopPackage_checkout_address_option').off('click');
    $('.WebshopPackage_checkout_address_option').on('click', function() {
        let id = $(this).attr('data-id');
        Webshop.selectAddress(id);
        // console.log('WebshopPackage_checkout_address_option click: ' + id);
    });

    $('#WebshopPackage_checkout_acceptTermsAndConditions').off('click');
    $('#WebshopPackage_checkout_acceptTermsAndConditions').on('click', function() {
        Webshop.acceptTermsAndConditions();
        // let id = $(this).attr('data-id');
        // Webshop.selectAddress(id);
        // console.log('WebshopPackage_checkout_acceptTermsAndConditions click');
    });

	$('body').off('keypress', '#webshop_search_term');
	$('body').on('keypress', '#webshop_search_term', function(event) {
        if (event.keyCode === 13) {
            console.log();
            $('#webshop_search_submit').click();
        }
    });

    // $('#defaultLightbox').show();

    $('#WebshopPackage_checkout_mobile').mask('+9999999999999999999999');

    // WebshopSearch.searchInValueToTermPlaceholder();

    // $('#webshop_search_category').off('change');
    // $('#webshop_search_category').on('change', function() {
    //     console.log($(this).val());
    //     // WebshopSearch.searchInValueToTermPlaceholder();
    // });
    // $('body').on('click', '.doNotTriggerHref', function(e) {
    //     e.preventDefault();
    // });

    // $('.nicEdit-main').off('blur');

    // $('.nicEdit-main').on('blur', function() {
    //     console.log('alma!!!');
    //     var content1 = nicEditors.findEditor("WebshopPackage_editProduct_description").getContent();
    //     $('#WebshopPackage_editProduct_description').html(content1);
    //     var content2 = nicEditors.findEditor("WebshopPackage_editProduct_descriptionEn").getContent();
    //     $('#WebshopPackage_editProduct_descriptionEn').html(content2);
    // });

    $('body').off('keyup', '#webshop_search_term');
    $('body').on('keyup', '#webshop_search_term', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            e.stopPropagation();
            // WebshopSearch.search();
            // WebshopSearch.search();
        } else {
            WebshopProductListAjaxTimer.timedAjaxReload();
        }
    });

    $('.webshopTriggerProductInfo').off('click');
    $('.webshopTriggerProductInfo').on('click', function(e) {
        e.preventDefault();
        ProductInfo.show($(this).attr('data-id'));
    });

    // $('#webshop_search_submit').click(function() {
    //     WebshopSearch.search();
    // });
});
</script>
