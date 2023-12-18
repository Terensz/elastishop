<!-- <div class="widgetWrapper">
    <div class="widgetWrapper-info">
        <?php echo trans('webshop.config.advices'); ?>
    </div>

    <div class="grid-container" id="AdminWebshopConfig-list" onclick="AdminWebshopConfig.edit(event, false);"></div>
</div> -->


<div class="card">
    <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white"><?php echo trans('information'); ?></h6>
        </div>
    </div>
    <div class="card-body">
        <span>
        <?php echo trans('webshop.config.advices'); ?>
        </span>
    </div>
</div>

<div class="grid-container" id="AdminWebshopConfig-list" onclick="AdminWebshopConfig.edit(event, false);"></div>

<script>
var AdminWebshopConfig = {
    list: function() {
        // e.preventDefault();
        // console.log('AdminWebshopConfig.edit');
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $httpDomain; ?>/admin/webshop/config/list',
            'data': {
                // 'prevShipmentId': prevShipmentId
                // 'lastShipmentId': lastShipmentId
            },
            'async': false,
            'success': function(response) {
                // console.log(response);
                $('#AdminWebshopConfig-list').html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    edit: function(e, submitted) {
        e.preventDefault();
        var formData = {};
        if (submitted === true) {
            var form = $('#WebshopPackage_editConfig_form');
            formData = form.serialize();
        }
        var additionalData = {
            'submitted': (submitted ? 'true' : 'false')
        };
        ajaxData = formData + '&' + $.param(additionalData);
        // console.log('AdminWebshopConfig.edit');
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $httpDomain; ?>/admin/webshop/config/edit',
            'data': ajaxData,
            'async': false,
            'success': function(response) {
                // console.log(response);
                // $('#').html(response.view);
                if (submitted) {
                    $('#editorModal').modal('hide');
                    AdminWebshopConfig.list();
                } else {
                    ElastiTools.checkResponse(response);
                    ElastiTools.fillModal(response, null);
                    $('#editorModal').modal('show');
                }
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    }
};
    // almaDocReady
$(document).ready(function() {
    AdminWebshopConfig.list();
    // $('.multiselect-input').select2({
    //     placeholder: 'Select an option'
    // });

});
</script>