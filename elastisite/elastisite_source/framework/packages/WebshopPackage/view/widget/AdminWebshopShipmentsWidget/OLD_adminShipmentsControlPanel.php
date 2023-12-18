<form name="WebshopPackage_shipmentSearch_form" id="WebshopPackage_shipmentSearch_form" method="POST" action="" enctype="multipart/form-data">
    <div class="row formRow">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="WebshopPackage_shipmentSearch_code"><?php echo trans('code'); ?></label>
                <input name="WebshopPackage_shipmentSearch_code" id="WebshopPackage_shipmentSearch_code" type="text"
                    class="inputField form-control" value="<?php echo ''; ?>" aria-describedby="" placeholder="">
            </div>
        </div>

    </div>
    <div class="row formRow">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="WebshopPackage_shipmentSearch_status"><?php echo trans('status'); ?></label>
                <select name="WebshopPackage_shipmentSearch_status" id="WebshopPackage_shipmentSearch_status" class="inputField form-control">
                    <option value="*all*"><?php echo trans('all'); ?></option>
<?php 
foreach ($statuses as $statusIndex => $statusProperties):
?>
                    <option value="<?php echo $statusIndex; ?>"><?php echo trans($statusProperties['adminTitle']); ?></option>
<?php
endforeach
?>
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
        </div>
    </div>
    <div class="row formRow">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <button id="WebshopPackage_shipmentSearch_submit" style="width: 200px;"
                    type="button" class="btn btn-secondary btn-block"
                    onclick="ShipmentSearch.search();"><?php echo trans('search'); ?></button>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
            </div>
        </div>
    </div>
</form>
<script>
    console.log('controlPanel!!');
var AdminShipmentGrid = {
    alma: function() {
        console.log('alma!!!!!!wrwer');
    }
};
var ShipmentSearch = {
    getParameters: function() {
        return {
            'searchMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/shipment/search',
            'formName': 'WebshopPackage_shipmentSearch_form'
        };
    },
    search: function(page) {
        var params = ShipmentSearch.getParameters();
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
                console.log(response.view);
                $('#adminShipmentGrid').html(response.view);
                $('#editorModal').modal('hide');
            },
            'error': function(request) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
};
</script>
