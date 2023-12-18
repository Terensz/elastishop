<div class="widgetWrapper">
    <div id="adminProductGrid">
    <?php echo $renderedGrid; ?>
    </div>
</div>

<script>
// var ProductTextEditor = {
//     copyTeaserToTextarea: function() {
//         var content = nicEditors.findEditor("ArticlePackage_article_teaser").getContent();
//         $('#ArticlePackage_article_teaser').html(content);
//     },
//     copyBodyToTextarea: function() {
//         var content = nicEditors.findEditor("ArticlePackage_article_body").getContent();
//         $('#ArticlePackage_article_body').html(content);
//     },
//     save: function(articleId) {
//         ArticleEditPanel.copyTeaserToTextarea();
//         ArticleEditPanel.copyBodyToTextarea();
//         ArticleEdit.save(articleId);
//     }
// };

var ProductModal = {
    switchTab: function(e, tabId) {
        // return ;
        console.log('switchTab (AdminWebshopProductsWidget/widget.php)');
        if (e != null) {
            e.preventDefault();
        }

        $('.productTab').each(function() {
            if ($(this).attr('data-tabid') == tabId) {
                $(this).addClass('active');
                // $(this).removeClass('inactive');
                $('.product-' + $(this).attr('data-tabid') + '-container').show();
            } else {
                // $(this).addClass('inactive');
                $(this).removeClass('active');
                $('.product-' + $(this).attr('data-tabid') + '-container').hide();
            }
        });
    }
};

var ProductPrice = {
    suggestNet: function() {

    },
    getParameters: function() {
        return {
            'listResponsePath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/productPrice/list',
            'newResponsePath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/productPrice/new',
            'activateResponsePath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/productPrice/activate',
            'deleteResponsePath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/productPrice/delete',
            'containerSelector': '.product-price-container',
            'newResponseSelector': '#newProductPrice-form'
        };
    },
    show: function() {
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
    new: function(e) {
        e.preventDefault();
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
    save: function(e) {
        e.preventDefault();
        LoadingHandler.start();
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
                LoadingHandler.stop();
                if (response.data.formIsValid == true) {
                    $('#newProductPrice-button').show();
                    $(params.newResponseSelector).html('');
                    console.log('Call ProductPrice.show 2');
                    ProductPrice.show();
                } else {
                    $(params.newResponseSelector).html(response.view);
                }
                // AdminProductGrid.list(true);
                AdminWebshopProductsDataGrid.list(true);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    cancelNew: function(e) {
        e.preventDefault();
        // console.log('cancelnew');
        $('#newProductPrice-form').html('');
        $('#newProductPrice-button').show();
    },
    activate: function(e, productId, productPriceId) {
        e.preventDefault();
        var params = ProductPrice.getParameters();
        $.ajax({
            'type' : 'POST',
            'url' : params.activateResponsePath,
            'data': {'productId': productId, 'productPriceId': productPriceId},
            'async': true,
            'success': function(response) {
                console.log('Call ProductPrice.show 3');
                ProductPrice.show();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    delete: function(e, id) {
        e.preventDefault();
        var params = ProductPrice.getParameters();
        $.ajax({
            'type' : 'POST',
            'url' : params.deleteResponsePath,
            'data': {'id': id},
            'async': true,
            'success': function(response) {
                console.log('Call ProductPrice.show 4');
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
            'listResponsePath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/productImage/list',
            'newResponsePath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/productImage/new',
            'setAsMainResponsePath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/productImage/setAsMain',
            'deleteResponsePath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/productImage/delete/',
            'containerSelector': '.product-image-container'
            // 'newResponseSelector': '#newProductImage-form'
        };
    },
    show: function() {
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
    new: function(e) {
        e.preventDefault();
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
    delete: function(e, id) {
        e.preventDefault();
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
    setAsMain: function(e, productId, productImageId) {
        e.preventDefault();
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
        // console.log('refresh');
        ProductImage.show();
    },
    upload: function(event) {
        var files = event.target.files;
        var url = '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/productImage/upload/' + $('#productId').html();
        var file = files[0];
        var upload = new Upload(file);
        if (file !== undefined) {
            upload.doUpload('WebshopPackage_uploadProductImage_file', url, 'ProductImage.refresh', null);
            ProductImage.show();
            LoadingHandler.start();
            // AdminFaviconWidget.call();
            // $('#editorModal').modal('hide');
        }
    }
};

var ProductEmail = {
    getParameters: function() {
        return {
            'listResponsePath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/productEmail/list',
            'newResponsePath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/productEmail/new',
            'editResponsePath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/productEmail/edit',
            'deleteResponsePath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/productEmail/delete',
            'containerSelector': '.product-email-container',
            'newResponseSelector': '#newProductEmail-form'
        };
    },
    show: function() {
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
    new: function(e) {
        e.preventDefault();
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
    save: function(e) {
        e.preventDefault();
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
                    console.log('Call ProductPrice.show 5');
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
    cancelNew: function(e) {
        e.preventDefault();
        // console.log('cancelnew');
        $('#newProductPrice-form').html('');
        $('#newProductPrice-button').show();
    },
    delete: function(e, id) {
        e.preventDefault();
        var params = ProductPrice.getParameters();
        $.ajax({
            'type' : 'POST',
            'url' : params.deleteResponsePath,
            'data': {'id': id},
            'async': true,
            'success': function(response) {
                console.log('Call ProductPrice.show 6');
                ProductPrice.show();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
};

$(document).ready(function() {
    // $('#WebshopPackage_newProductPrice_netPrice').on('input', function () {
    //     console.log('WebshopPackage_newProductPrice_netPrice input');
    //     // Frissítsd a vat értékét
    //     var vat = parseFloat($('#WebshopPackage_newProductPrice_vat').val()) || 0;

    //     // Számold ki az ajánlott nettó értéket
    //     var inputNetPrice = parseFloat($(this).val()) || 0;
    //     var suggestedNetPrice = Math.round(inputNetPrice / (1 + vat / 100));

    //     // Ha a kiszámolt érték változott, akkor frissítsd a mezőt
    //     if (suggestedNetPrice !== inputNetPrice) {
    //         $('#WebshopPackage_newProductPrice_netPriceSuggestion').html(suggestedNetPrice);
    //     } else {
    //         $('#WebshopPackage_newProductPrice_netPriceSuggestion').html('');   
    //     }
    // });

    // $('body').on('click', '.doNotTriggerHref', function(e) {
    //     e.preventDefault();
    // });

    // $('.nicEdit-main').blur(function() {
    $('.nicEdit-main').on('blur', function() {
        console.log('.nicEdit-main blur');
        var content1 = nicEditors.findEditor("WebshopPackage_editProduct_description").getContent();
        $('#WebshopPackage_editProduct_description').html(content1);

        console.log('content1:', content1);
        // if (content1 != '<br>') {
        //     console.log(content1);
        //     $('#WebshopPackage_editProduct_description').html(content1);
        // } else {
        //     $('#WebshopPackage_editProduct_description').html('');
        // }
        var content2 = nicEditors.findEditor("WebshopPackage_editProduct_descriptionEn").getContent();
        $('#WebshopPackage_editProduct_descriptionEn').html(content2);

        console.log('content2:', content2);
        // if (content2 != '<br>') {
        //     $('#WebshopPackage_editProduct_descriptionEn').html(content2);
        // } else {
        //     $('#WebshopPackage_editProduct_descriptionEn').html('');
        // }
    });

    // $(document).on('change', '#WebshopPackage_uploadProductImage_file', function(e) {
    //     e.stopPropagation();
    //     console.log('WebshopPackage_uploadProductImage_file click!!!');
    //     ProductImage.upload($(this));
    // });

    $('textarea').keypress(function(e) {
        if (e.which == 13) {
            e.stopPropagation();
        }
    });
});
</script>
