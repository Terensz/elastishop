<form name="ToolPackage_imageSearch_form" id="ToolPackage_imageSearch_form" method="POST" action="" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="ToolPackage_imageSearch_all"><?php echo trans('search.all'); ?></label>
                <input name="ToolPackage_imageSearch_all" id="ToolPackage_imageSearch_all" type="text"
                    class="inputField form-control" value="<?php echo ''; ?>" aria-describedby="" placeholder="">
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="ToolPackage_imageSearch_all"></label>
                <button id="ToolPackage_imageSearch_submit" style="width: 200px;"
                    type="button" class="btn btn-secondary btn-block"
                    onclick="ImageSearch.search();"><?php echo trans('search'); ?></button>
            </div>
        </div>
    </div>
</form>
<script>
var ImageSearch = {
    getParameters: function() {
        return {
            'searchMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/image/search',
            'formName': 'ToolPackage_imageSearch_form'
        };
    },
    search: function(page) {
        var params = ImageSearch.getParameters();
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
                $('#adminImagesGrid').html(response.view);
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
