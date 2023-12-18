<form name="WebshopPackage_productSearch_form" id="WebshopPackage_productSearch_form" method="POST" action="" enctype="multipart/form-data">
    <div class="row formRow">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="WebshopPackage_productSearch_name"><?php echo trans('name'); ?></label>
                <input name="WebshopPackage_productSearch_name" id="WebshopPackage_productSearch_name" type="text"
                    class="inputField form-control" value="<?php echo ''; ?>" aria-describedby="" placeholder="">
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="WebshopPackage_productSearch_productCategory"><?php echo trans('product.category'); ?></label>
                <select name="WebshopPackage_productSearch_productCategory" id="WebshopPackage_productSearch_productCategory" class="inputField form-control">
                    <option value="*all*"><?php echo trans('all'); ?></option>
                    <option value="*null*"><?php echo trans('main.category'); ?></option>
<?php
foreach ($productCategories as $product) {
?>
                    <option value="<?php echo $product->getId(); ?>"><?php echo $product->getName(); ?></option>
<?php
}
?>
                </select>
            </div>
        </div>
    </div>
    <div class="row formRow">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="WebshopPackage_productSearch_status"><?php echo trans('status'); ?></label>
                <select name="WebshopPackage_productSearch_status" id="WebshopPackage_productSearch_status" class="inputField form-control">
                    <option value="*all*"><?php echo trans('all'); ?></option>
                    <option value="1"><?php echo trans('active'); ?></option>
                    <option value="2"><?php echo trans('out.of.stock'); ?></option>
                    <option value="3"><?php echo trans('discontinued'); ?></option>
                    <option value="0"><?php echo trans('disabled'); ?></option>
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
        </div>
    </div>
    <div class="row formRow">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <button id="WebshopPackage_productSearch_submit" style="width: 200px;"
                    type="button" class="btn btn-secondary btn-block"
                    onclick="ProductSearch.search();"><?php echo trans('search'); ?></button>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
            </div>
        </div>
    </div>
</form>
<script>
var ProductSearch = {
    getParameters: function() {
        return {
            'searchMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/product/search',
            'formName': 'WebshopPackage_productSearch_form'
        };
    },
    search: function(page) {
        var params = ProductSearch.getParameters();
        var ajaxData = {};
        var form = $('#' + params.formName);
        // console.log(params.formName);
        var formData = form.serialize();
        var additionalData = {
            'page': page
        };
        ajaxData = formData + '&' + $.param(additionalData);
        // console.log(ajaxData);
        $.ajax({
            'type' : 'POST',
            'url' : params.searchMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                $('#adminProductGrid').html(response.view);
                $('#editorModal').modal('hide');
                // console.log(response.view);
            },
            'error': function(request) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
};
</script>
