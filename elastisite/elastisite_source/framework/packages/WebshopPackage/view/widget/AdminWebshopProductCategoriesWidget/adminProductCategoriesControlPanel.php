<form name="WebshopPackage_productCategorySearch_form" id="WebshopPackage_productCategorySearch_form" method="POST" action="" enctype="multipart/form-data">
    <div class="row formRow">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="WebshopPackage_productCategorySearch_name"><?php echo trans('name'); ?></label>
                <input name="WebshopPackage_productCategorySearch_name" id="WebshopPackage_productCategorySearch_name" type="text"
                    class="inputField form-control" value="<?php echo ''; ?>" aria-describedby="" placeholder="">
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="WebshopPackage_productCategorySearch_productCategory"><?php echo trans('parent.product.category'); ?></label>
                <select name="WebshopPackage_productCategorySearch_productCategory" id="WebshopPackage_productCategorySearch_productCategory" class="inputField form-control">
                    <option value="*all*"><?php echo trans('all'); ?></option>
                    <option value="*null*"><?php echo trans('main.category'); ?></option>
<?php
foreach ($productCategories as $productCategory) {
?>
                    <option value="<?php echo $productCategory->getId(); ?>"><?php echo $productCategory->getName(); ?></option>
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
                <label for="WebshopPackage_productCategorySearch_status"><?php echo trans('status'); ?></label>
                <select name="WebshopPackage_productCategorySearch_status" id="WebshopPackage_productCategorySearch_status" class="inputField form-control">
                    <option value="*all*"><?php echo trans('all'); ?></option>
                    <option value="1"><?php echo trans('active'); ?></option>
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
                <button id="WebshopPackage_productCategorySearch_submit" style="width: 200px;"
                    type="button" class="btn btn-secondary btn-block"
                    onclick="ProductCategorySearch.search();"><?php echo trans('search'); ?></button>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
            </div>
        </div>
    </div>
</form>
<script>
var ProductCategorySearch = {
    getParameters: function() {
        return {
            'searchMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/productCategory/search',
            'formName': 'WebshopPackage_productCategorySearch_form'
        };
    },
    search: function(page) {
        var params = ProductCategorySearch.getParameters();
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
                $('#adminProductCategoryGrid').html(response.view);
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
