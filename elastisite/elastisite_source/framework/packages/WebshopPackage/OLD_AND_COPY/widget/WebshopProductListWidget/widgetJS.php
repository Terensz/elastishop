<script>
var WebshopSearch = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $httpDomain; ?>/webshop/productList',
            'responseSelector': '#webshopProductList'
        };
    },
    // search: function() {
    //     console.log('WebshopSearch.search');
    //     var params = WebshopSearch.getParameters();
    //     var form = $('#webshop_search_form');
    //     ajaxData = form.serialize();

    //     $.ajax({
    //         'type' : 'POST',
    //         'url' : params.responseMethodPath,
    //         'data': ajaxData,
    //         'async': true,
    //         'success': function(response) {
    //             // console.log(response);
    //             // var params = WebshopSearch.getParameters();
    //             // console.log('success!!!!!!', response);
    //             // console.log(response);
    //             $('#widgetContainer-mainContent').html(response.view);
    //             $(params.responseSelector).html(response.view);
    //         },
    //         'error': function(request, error) {
    //             console.log(request);
    //             console.log(" Can't do because: " + error);
    //         },
    //     });
    // },
    searchInValueToTermPlaceholder: function() {
        $('#webshop_search_term').attr('placeholder', $('#webshop_search_category').find(":selected").html());
    },
    search: function() {
        console.log('WebshopSearch.search() !!!!!!!!!');
        if ($('#webshop_search_term').val() != '') {
            let category = $('#webshop_search_category').val();
            if (typeof(category) == 'undefined') {
                category = '<?php echo $listAllCategorySlug; ?>';
            }
            let searchString = $('#webshop_search_term').val();
            let searchLink = '<?php echo $searchLinkBase; ?>';
            searchLink = searchLink.replace('{category}', category);
            searchLink = searchLink.replace('{searchString}', searchString);
            $('#webshopSearchLink').prop('href', '<?php echo $httpDomain; ?>/' + searchLink);

            // let url = window.location.href;
            // let httpDomain = '<?php echo $httpDomain; ?>';
            // let urlParams = url.replace(httpDomain, '');
            //console.log(urlParams);

            // console.log($('#webshopSearchLink').prop('href'));
            
            $('#webshopSearchLink').click();
        } else {
            $('#webshopSearchLink').prop('href', '<?php echo $httpDomain.'/webshop'; ?>');
            $('#webshopSearchLink').click();
        }
    }
};

var ProductList = {
    addToCart: function(event, offerId) {
        event.preventDefault();
        LoadingHandler.start();
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $httpDomain; ?>/webshop/addToCart',
            'data': {'offerId': offerId},
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                WebshopSideCartWidget.call(false);
                Structure.throwToast(response.data.toastTitle, response.data.toastBody);
                ProductList.refreshAddtocartButtons(response.data.cartOfferIds);
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
    removeFromCart: function(event, offerId) {
        event.preventDefault();
        LoadingHandler.start();
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $httpDomain; ?>/webshop/removeFromCart',
            'data': {'offerId': offerId},
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                WebshopSideCartWidget.call(false);
                Structure.throwToast(response.data.toastTitle, response.data.toastBody);
                ProductList.refreshAddtocartButtons(response.data.cartOfferIds);
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
    refreshAddtocartButtons: function(cartOfferIds) {
        let links = $('.addToCartButtonContainer');
        for (let i = 0; i < links.length; i++) {
            let link = links[i];
            let addtocartId = $(link).attr('data-addtocartid');
            let offerId = addtocartId.replace('addtocart-', '');
            if (inArray(offerId, cartOfferIds)) {
                $(link).html(ProductList.displayAlreadyAtCart());
            } else {
                $(link).html(ProductList.displayAddtocartLink(offerId));
            }
        }
    },
    displayAddtocartLink: function(offerId) {
        return '<a href="" onclick="ProductList.addToCart(event, ' + offerId + ');"><?php echo trans('put.to.cart'); ?></a>';
    },
    displayAlreadyAtCart: function() {
        return '<?php echo '<i>'.trans('already.at.cart').'</i>'; ?>';
    }
};

var ProductInfo = {
    show: function(productId) {
        // console.log('show id: ' + productId);
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $httpDomain; ?>/webshop/productInfo/widget',
            'data': {'productId': productId},
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                $('#productInfoModalLabel').html(response.data.title);
                $('#productInfoModalBody').html(response.view);
                $('#productInfoModal').modal('show');
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    }
};
<?php if ($grantedViewProjectAdminContent): ?>
var AdminWebshopProductList = {
    edit: function(event, id) {
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $httpDomain; ?>/admin/webshop/product/edit',
            'data': {'id': id },
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                ElastiTools.fillModal(response, null);
                if (response.hasOwnProperty('data')) {
                    // FormValidator.displayErrors('#' + params.formName, response.data.messages);
                    if (response.data.formIsValid === true) {
                        $('#editorModal').modal('hide');
                        // AdminWebshopProductsGrid.list(true);
                    } else {
                        $('#editorModal').modal('show');
                    }
                }
            },
            'error': function(request) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
};

// var ProductModal = {
//     switchTab: function(e, tabId) {
//         console.log('switchTab (WebshopProductListWidget/widgetJS.php)');
//         if (e != null) {
//             e.preventDefault();
//         }
//         if (tabId == 'property') {
//             $('.productTab-property').addClass('productTab-active');
//             $('.productTab-property').removeClass('productTab-inactive');
//             $('.productTab-price').addClass('productTab-inactive');
//             $('.productTab-price').removeClass('productTab-active');
//             $('.productTab-image').addClass('productTab-inactive');
//             $('.productTab-image').removeClass('productTab-active');
//             $('.productProperty-container').show();
//             $('.productPrice-container').hide();
//             $('.productImage-container').hide();
//         }
//         if (tabId == 'price') {
//             $('.productTab-property').addClass('productTab-inactive');
//             $('.productTab-property').removeClass('productTab-active');
//             $('.productTab-price').addClass('productTab-active');
//             $('.productTab-price').removeClass('productTab-inactive');
//             $('.productTab-image').addClass('productTab-inactive');
//             $('.productTab-image').removeClass('productTab-active');
//             $('.productProperty-container').hide();
//             $('.productPrice-container').show();
//             $('.productImage-container').hide();
//         }
//         if (tabId == 'image') {
//             $('.productTab-property').addClass('productTab-inactive');
//             $('.productTab-property').removeClass('productTab-active');
//             $('.productTab-price').addClass('productTab-inactive');
//             $('.productTab-price').removeClass('productTab-active');
//             $('.productTab-image').addClass('productTab-active');
//             $('.productTab-image').removeClass('productTab-inactive');
//             $('.productProperty-container').hide();
//             $('.productPrice-container').hide();
//             $('.productImage-container').show();
//         }
//     }
// };

var ProductPrice = {
    getParameters: function() {
        return {
            'listResponsePath': '<?php echo $httpDomain; ?>/admin/webshop/productPrice/list',
            'newResponsePath': '<?php echo $httpDomain; ?>/admin/webshop/productPrice/new',
            'activateResponsePath': '<?php echo $httpDomain; ?>/admin/webshop/productPrice/activate',
            'deleteResponsePath': '<?php echo $httpDomain; ?>/admin/webshop/productPrice/delete',
            'containerSelector': '.productPrice-container',
            'newResponseSelector': '#newProductPrice-form'
        };
    },
    list: function() {
        var params = ProductPrice.getParameters();
        var productId = $('#productId').html();
        $.ajax({
            'type' : 'POST',
            'url' : params.listResponsePath,
            'data': {'productId': productId},
            'async': true,
            'success': function(response) {
                $(params.containerSelector).html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    new: function() {
        var params = ProductPrice.getParameters();
        var productId = $('#productId').html();
        $.ajax({
            'type' : 'POST',
            'url' : params.newResponsePath,
            'data': {'productId': productId},
            'async': true,
            'success': function(response) {
                $(params.newResponseSelector).html(response.view);
                $('#newProductPrice-button').hide();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    save: function() {
        var productId = $('#productId').html();
        var params = ProductPrice.getParameters();
        var ajaxData = {};
        var form = $('#WebshopPackage_newProductPrice_form');
        var formData = form.serialize();
        var additionalData = {
            'productId': productId
        };
        ajaxData = formData + '&' + $.param(additionalData);
        $.ajax({
            'type' : 'POST',
            'url' : params.newResponsePath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                if (response.data.formIsValid == true) {
                    $('#newProductPrice-button').show();
                    $(params.newResponseSelector).html('');
                    console.log('Call ProductPrice.show 1');
                    ProductPrice.show();
                } else {
                    $(params.newResponseSelector).html(response.view);
                }
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    cancelNew: function() {
        // console.log('cancelnew');
        $('#newProductPrice-form').html('');
        $('#newProductPrice-button').show();
    },
    activate: function(productId, productPriceId) {
        var params = ProductPrice.getParameters();
        $.ajax({
            'type' : 'POST',
            'url' : params.activateResponsePath,
            'data': {'productId': productId, 'productPriceId': productPriceId},
            'async': true,
            'success': function(response) {
                console.log('Call ProductPrice.show 7');
                ProductPrice.show();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    delete: function(id) {
        var params = ProductPrice.getParameters();
        $.ajax({
            'type' : 'POST',
            'url' : params.deleteResponsePath,
            'data': {'id': id},
            'async': true,
            'success': function(response) {
                console.log('Call ProductPrice.show 8');
                ProductPrice.show();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
};

var ProductImage = {
    getParameters: function() {
        return {
            'listResponsePath': '<?php echo $httpDomain; ?>/admin/webshop/productImage/list',
            'newResponsePath': '<?php echo $httpDomain; ?>/admin/webshop/productImage/new',
            'setAsMainResponsePath': '<?php echo $httpDomain; ?>/admin/webshop/productImage/setAsMain',
            'deleteResponsePath': '<?php echo $httpDomain; ?>/admin/webshop/productImage/delete/',
            'containerSelector': '.productImage-container'
            // 'newResponseSelector': '#newProductImage-form'
        };
    },
    list: function() {
        var params = ProductImage.getParameters();
        var productId = $('#productId').html();
        $.ajax({
            'type' : 'POST',
            'url' : params.listResponsePath,
            'data': {'productId': productId},
            'async': true,
            'success': function(response) {
                // console.log('ProductImage.list!!!');
                // console.log(response);
                $(params.containerSelector).html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    new: function() {
        var params = ProductImage.getParameters();
        var productId = $('#productId').html();
        $.ajax({
            'type' : 'POST',
            'url' : params.newResponsePath,
            'data': {'productId': productId},
            'async': true,
            'success': function(response) {
                console.log(params.newResponsePath);
                $('#newProductImage-form').html(response.view);
                $('#newProductImage-button').hide();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    delete: function(id) {
        var params = ProductImage.getParameters();
        $.ajax({
            'type' : 'POST',
            'url' : params.deleteResponsePath,
            'data': {'id': id},
            'async': true,
            'success': function(response) {
                ProductImage.show();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    setAsMain: function(productId, productImageId) {
        var params = ProductImage.getParameters();
        $.ajax({
            'type' : 'POST',
            'url' : params.setAsMainResponsePath,
            'data': {'productId': productId, 'productImageId': productImageId},
            'async': true,
            'success': function(response) {
                ProductImage.show();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    refresh: function() {
        console.log('refresh');
    },
    upload: function(event) {
        var files = event.target.files;
        var url = '<?php echo $httpDomain; ?>/admin/webshop/productImage/upload/' + $('#productId').html();
        var file = files[0];
        var upload = new Upload(file);
        if (file !== undefined) {
            upload.doUpload('WebshopPackage_uploadProductImage_file', url, 'ProductImage.refresh', null);
            ProductImage.show();
            // AdminFaviconWidget.call();
            // $('#editorModal').modal('hide');
        }
    }
};
<?php endif; ?>

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
                    WebshopSearch.search();
                }
            }, 800);
        }
        return result;
    }
};

$(document).ready(function() {
    WebshopSearch.searchInValueToTermPlaceholder();

    $('#webshop_search_category').off('change');
    $('#webshop_search_category').on('change', function() {
        console.log($(this).val());
        WebshopSearch.searchInValueToTermPlaceholder();
    });
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

    $('#webshop_search_submit').click(function() {
        WebshopSearch.search();
        // if ($('#webshop_search_term').val() != '') {
        //     // $('#webshopSearchLink').prop('href', '<?php echo $httpDomain.'/'.$searchLinkBase.'/'; ?>' 
        //     //     + $('#webshop_search_term').val());
        //     // $('#webshopSearchLink').click();
        //     WebshopSearch.search();
        // }
    });
});
</script>